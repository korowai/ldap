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

use Korowai\Lib\Ldap\Adapter\ResultReferralIterator as BaseIterator;

/**
 * Iterates through referrals of an ldap result reference.
 *
 * Only one instance of ``ResultReferralIterator`` should be used for a given
 * ``ResultReference``.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferralIterator extends BaseIterator
{
    /**
     * Initializes the ``ResultReferralIterator``.
     *
     * @param ResultReference $reference An ldap reference containing the referrals
     */
    public function __construct(ResultReference $reference)
    {
        parent::__construct($reference);
    }
}

// vim: syntax=php sw=4 ts=4 et:
