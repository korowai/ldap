<?php
/**
 * @file packages/ldaplib/LdapInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

use Korowai\Lib\Ldap\Adapter\BindingInterface;
use Korowai\Lib\Ldap\Adapter\EntryManagerInterface;
use Korowai\Lib\Ldap\Adapter\AdapterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LDAP interface
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapInterface extends
    BindingInterface,
    EntryManagerInterface,
    AdapterInterface
{
    /**
     * Returns adapter
     * @return AdapterInterface Adapter
     */
    public function getAdapter() : AdapterInterface;
}

// vim: syntax=php sw=4 ts=4 et:
