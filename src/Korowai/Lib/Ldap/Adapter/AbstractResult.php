<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/AbstractResult.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractResult implements ResultInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntries(bool $use_keys = true) : array
    {
        return iterator_to_array($this, $use_keys);
    }

    /**
     * Makes the ``Result`` object iterable
     */
    public function getIterator()
    {
        foreach ($this->getResultEntryIterator() as $key => $entry) {
            yield $key => $entry->toEntry();
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
