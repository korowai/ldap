<?php
/**
 * @file packages/ldaplib/Adapter/QueryInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

use Korowai\Lib\Ldap\Adapter\ResultInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface QueryInterface
{
    /**
     * Executes query and returns result.
     *
     * @return ResultInterface
     *
     * @throws LdapException
     */
    public function execute() : ResultInterface;

    /**
     * Returns the result of last execution of the query, calls execute() if
     * necessary.
     *
     * @return ResultInterface
     *
     * @throws LdapException
     */
    public function getResult() : ResultInterface;
}

// vim: syntax=php sw=4 ts=4 et:
