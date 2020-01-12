<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/Mock/ResultReference.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\Mock;

use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferralIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ReferralsIterationInterface;

/**
 * A reference object for ldap search Result.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReference extends ResultRecord implements ResultReferenceInterface, ReferralsIterationInterface
{
    /** @var array */
    private $referrals;
    /** @var ResultReferralIterator */
    private $iterator;

    /**
     * Returns a ResultReference object made out of *$reference* argument.
     *
     * @param mixed $reference an instance of ResultReferenceInterface or an array.
     *
     * @return ResultReference
     * @throws \InvalidArgumentException when *$reference* is of wrong type
     */
    public static function make($reference)
    {
        if ($reference instanceof ResultReference) {
            return $reference;
        } elseif ($reference instanceof ResultReferenceInterface) {
            return new static($reference->getDn(), $reference->getReferrals());
        } elseif (is_array($reference)) {
            return static::createWithArray($reference);
        } else {
            $type = gettype($reference);
            $type = $type === 'object' ? get_class($reference) : $type;
            $msg = 'parameter 1 to ResultReference::make() must be ' .
                   'an instance of ResultReferenceInterface or an array, not ' . $type;
            throw new \InvalidArgumentException($msg);
        }
    }

    /**
     * Creates ResultReference instance using an array as argument.
     *
     * The *$reference* shall have form:
     *
     *      $reference = [
     *          'dn' => '...',
     *          'referrals' => [
     *              'attr1' => [...],
     *              ...
     *          ]
     *      ]
     *
     * or just:
     *
     *      $reference = [
     *          'dn' => '...',
     *          'attr1' => [...],
     *          ...
     *      ]
     *
     * @param array $reference
     * @return ResultReference
     */
    public static function createWithArray(array $reference)
    {
        $dn = $reference['dn'];

        if (($referrals = $reference['referrals'] ?? null) === null) {
            // filter-out non-referral items
            $referrals = array_filter($reference, function ($item, $key) {
                return is_int($key);
            }, ARRAY_FILTER_USE_BOTH);
        }

        return new static($dn, $referrals);
    }

    /**
     * Initializes the ``ResultEntry`` instance
     *
     * @param string $dn
     * @param array $referrals
     */
    public function __construct(string $dn, array $referrals = array())
    {
        $this->initResultRecord($dn);
        $this->referrals = $referrals;
    }

    /**
     * It always returns same instance. When used for the first
     * time, the iterator is set to point to the first referral of the
     * reference. For subsequent calls, the method just return the iterator
     * without altering its position.
     */
    public function getReferralIterator() : ResultReferralIteratorInterface
    {
        if (!isset($this->iterator)) {
            $this->iterator = new ResultReferralIterator($this);
        }
        return $this->iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function getReferrals() : array
    {
        return array_change_key_case($this->referrals, CASE_LOWER);
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Returns the result of ``current($this->referrals)``.
     */
    public function referrals_current()
    {
        return current($this->referrals);
    }

    /**
     * Returns the result of ``key($this->referrals)``.
     */
    public function referrals_key()
    {
        return key($this->referrals);
    }

    /**
     * Returns the result of ``next($this->referrals)``.
     */
    public function referrals_next()
    {
        return next($this->referrals);
    }

    /**
     * Returs the result of ``reset($this->referrals)``.
     */
    public function referrals_reset()
    {
        return reset($this->referrals);
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
