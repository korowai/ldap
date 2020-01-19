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

trait ResultLog
{
    protected $results = [];

    protected function clearResults()
    {
        $this->results = [];
    }

    protected function appendResult($result)
    {
        $this->results[] = $result;
    }

    protected function lastResult()
    {
        if (count($this->results) < 1) {
            return null;
        } else {
            return $this->results[count($this->results)-1];
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
