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

use Korowai\Lib\Ldap\Entry;
use Korowai\Lib\Ldap\EntryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultEntryToEntry
{
    /**
     * {@inheritdoc}
     */
    public function toEntry() : EntryInterface
    {
        return new Entry($this->getDn(), $this->getAttributes());
    }

    abstract public function getDn() : string;
    abstract public function getAttributes() : array;
}

// vim: syntax=php sw=4 ts=4 et:
