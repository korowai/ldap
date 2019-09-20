<?php
/**
 * @file packages/ldaplib/Adapter/ExtLdap/LastLdapException.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\Exception\LdapException;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LastLdapException
{
    protected static function lastLdapException(LdapLink $link)
    {
        $errno = $link->errno();
        $errstr = LdapLink::err2str($errno);
        return new LdapException($errstr, $errno);
    }
}

// vim: syntax=php sw=4 ts=4 et:
