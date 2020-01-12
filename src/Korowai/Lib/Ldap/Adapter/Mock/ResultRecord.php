<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/Mock/ResultRecord.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\Mock;

use Korowai\Lib\Ldap\Adapter\ResultRecordInterface;

/**
 * Common functions for ResultEntry and ResultReference.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultRecord implements ResultRecordInterface
{
    /** @var Result */
    private $dn;

    /**
     * Initializes the ``ResultRecord`` instance
     *
     * @param string $dn
     */
    protected function initResultRecord(string $dn)
    {
        $this->dn = $dn;
    }

    /**
     * {@inheritdoc}
     */
    public function getDn() : string
    {
        return $this->dn;
    }
}

// vim: syntax=php sw=4 ts=4 et:
