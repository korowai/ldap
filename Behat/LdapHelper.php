<?php
/**
 * @file Behat/LdapHelper.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Behat;

use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\Exception\LdapException;

trait LdapHelper
{
    use ExceptionLog, ResultLog;

    private $ldap = null;

    protected function initLdapHelper()
    {
        $this->clearExceptions();
        $this->clearResults();
    }

    protected function createLdapLinkWithConfig($config)
    {
        try {
            $this->ldap = Ldap::createWithConfig($config);
        } catch (\Exception $e) {
            $this->appendException($e);
        }
    }

    protected function bindWithArgs(...$args)
    {
        try {
            return $this->ldap->bind(...$args);
        } catch (\Exception $e) {
            $this->appendException($e);
            return false;
        }
    }

    protected function queryWithArgs(...$args)
    {
        try {
            $result = $this->ldap->query(...$args);
        } catch (\Exception $e) {
            $this->appendException($e);
            return false;
        }
        $this->appendResult($result);
        return $result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
