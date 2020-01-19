<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferralIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ReferralsIterationInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * Wrapper for ldap reference result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReference extends ResultRecord implements ResultReferenceInterface, ReferralsIterationInterface
{
    use LastLdapException;

    /** @var array */
    private $referrals;
    /** @var ResultReferralIterator */
    private $iterator;

    /**
     * Initializes the ``ResultReference`` instance
     *
     * @param  resource|null $reference
     * @param Result $result
     */
    public function __construct($reference, Result $result)
    {
        $this->initResultRecord($reference, $result);
        $this->referrals = null;
    }

    /**
     * It always returns same instance. When used for the first
     * time, the iterator is set to point to the first attribute of the entry.
     * For subsequent calls, the method just return the iterator without
     * altering its position.
     */
    public function getReferralIterator() : ResultReferralIteratorInterface
    {
        if (!isset($this->iterator)) {
            $this->iterator = new ResultReferralIterator($this);
        }
        return $this->iterator;
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Get next result reference
     *
     * @link http://php.net/manual/en/function.ldap-next-reference.php ldap_next_reference()
     */
    public function next_reference()
    {
        return $this->getResult()->getLink()->next_reference($this);
    }

    /**
     * Extract referrals from the reference message
     *
     * @link http://php.net/manual/en/function.ldap-parse-reference.php ldap_parse_reference()
     */
    public function parse_reference(&$referrals)
    {
        return $this->getResult()->getLink()->parse_reference($this, $referrals);
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd

    /**
     * Returns referrals
     * @return array
     * @throws LdapException thrown when ``parse_reference`` returns ``false``
     */
    public function getReferrals() : array
    {
        if (!isset($this->referrals)) {
            if ($this->parse_reference($referrals) === false) {
                throw static::lastLdapException($this->getResult()->getLink());
            }
            $this->referrals = $referrals;
        }
        return $this->referrals;
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Returns the result of ``current($this->referrals)``.
     */
    public function referrals_current()
    {
        return current($this->getReferrals());
    }

    /**
     * Returns the result of ``key($this->referrals)``.
     */
    public function referrals_key()
    {
        return key($this->getReferrals());
    }

    /**
     * Returns the result of ``next($this->referrals)``.
     */
    public function referrals_next()
    {
        $this->getReferrals();
        return next($this->referrals);
    }

    /**
     * Returs the result of ``reset($this->referrals)``.
     */
    public function referrals_reset()
    {
        $this->getReferrals();
        return reset($this->referrals);
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
