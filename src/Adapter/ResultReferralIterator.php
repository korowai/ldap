<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

use Korowai\Lib\Ldap\Adapter\ResultReferralIteratorInterface;

/**
 * An implementation of ResultReferralIteratorInterface which delegates
 * iteration to an instance of ReferralsIterationInterface.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferralIterator implements ResultReferralIteratorInterface
{
    /** @var ResultReference */
    private $reference;

    /**
     * Initializes the ``ResultReferralIterator``.
     *
     * @param ReferralsIterationInterface $reference An ldap reference containing the referrals
     */
    public function __construct(ReferralsIterationInterface $reference)
    {
        $this->reference = $reference;
    }

    /**
     * Returns the ``$reference`` provided to ``__construct`` at creation time.
     *
     * @eturn object The ``$reference`` provided to ``__construct`` at creation time.
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Returns current element.
     *
     * Should only be used on valid iterator.
     *
     * @return mixed
     *
     * @link http://php.net/manual/en/iterator.current.php Iterator::current
     */
    public function current()
    {
        return $this->getReference()->referrals_current();
    }

    /**
     * Returns the key of the current element.
     *
     * @return mixed
     *
     * @link http://php.net/manual/en/iterator.key.php Iterator::key
     */
    public function key()
    {
        return $this->getReference()->referrals_key();
    }

    /**
     * Moves the current position to the next element
     *
     * @link http://php.net/manual/en/iterator.next.php Iterator::next
     */
    public function next()
    {
        $this->getReference()->referrals_next();
    }

    /**
     * Rewinds back to the first element of the iterator
     *
     * @link http://php.net/manual/en/iterator.rewind.php Iterator::rewind
     */
    public function rewind()
    {
        $this->getReference()->referrals_reset();
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php Iterator::valid
     */
    public function valid()
    {
        return $this->getReference()->referrals_key() !== null;
    }
}

// vim: syntax=php sw=4 ts=4 et:
