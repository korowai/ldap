<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/ResultEntryToEntry.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
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

    public abstract function getDn() : string;
    public abstract function getAttributes() : array;
}

// vim: syntax=php sw=4 ts=4 et:
