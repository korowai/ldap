<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/AbstractResultEntry.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

use Korowai\Lib\Ldap\Adapter\ResultEntryInterface;
use Korowai\Lib\Ldap\Entry;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractResultEntry implements ResultEntryInterface
{
    /**
     * {@inheritdoc}
     */
    public function toEntry() : Entry
    {
        return new Entry($this->getDn(), $this->getAttributes());
    }
}

// vim: syntax=php sw=4 ts=4 et:
