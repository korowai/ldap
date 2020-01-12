<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/Mock/ResultEntryIterator.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\Mock;

use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultEntryIterator extends AbstractResultIterator implements ResultEntryIteratorInterface
{
    /**
     * Constructs ResultEntryIterator
     *
     * @param Result $result            The ldap search result which provides
     *                                  first entry in the entry chain
     */
    public function __construct(Result $result)
    {
        parent::__construct($result);
    }

    /**
     * Returns the ``$entry`` provided to ``__construct()`` at creation
     * @return mixed The ``$entry`` provided to ``__construct()`` at creation
     */
    public function getEntry()
    {
        return $this->current();
    }

    protected function getMethodForCurrent()
    {
        return 'entries_current';
    }

    protected function getMethodForNext()
    {
        return 'entries_next';
    }

    protected function getMethodForReset()
    {
        return 'entries_reset';
    }
}

// vim: syntax=php sw=4 ts=4 et:
