<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
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

    protected function searchWithArgs(...$args)
    {
        try {
            $result = $this->ldap->search(...$args);
        } catch (\Exception $e) {
            $this->appendException($e);
            return false;
        }
        $this->appendResult($result);
        return $result;
    }

    protected function compareWithArgs(...$args)
    {
        try {
            $result = $this->ldap->compare(...$args);
        } catch (\Exception $e) {
            $this->appendException($e);
            return false;
        }
        $this->appendResult($result);
        return $result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
