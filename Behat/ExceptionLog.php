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

trait ExceptionLog
{
    protected $exceptions = [];

    protected function clearExceptions()
    {
        $this->exceptions = [];
    }

    protected function appendException($e)
    {
        $this->exceptions[] = $e;
    }

    protected function lastException()
    {
        if (count($this->exceptions) < 1) {
            return null;
        } else {
            return $this->exceptions[count($this->exceptions)-1];
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
