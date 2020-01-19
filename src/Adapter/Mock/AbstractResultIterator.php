<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\Mock;

use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractResultIterator
{
    /** @var Result */
    private $result;

    /**
     * Constructs ResultEntryIterator
     *
     * @param Result $result The ldap search result which provides first entry in the entry chain
     *
     * The ``$result`` object is used by ``rewind()`` method.
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * Returns the ``$result`` provided to ``__construct()`` when the object
     * was created.
     *
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Return the current element, that is the current entry
     */
    public function current()
    {
        $method = $this->getMethodForCurrent();
        return call_user_func([$this->result, $method]);
    }

    /**
     * Return the key of the current element, that is DN of the current entry
     */
    public function key()
    {
        return ($current = $this->current()) !== false ? $current->getDn() : null;
    }

    /**
     * Move forward to next element
     */
    public function next()
    {
        $method = $this->getMethodForNext();
        call_user_func([$this->result, $method]);
    }

    /**
     * Rewind the iterator to the first element
     */
    public function rewind()
    {
        $method = $this->getMethodForReset();
        call_user_func([$this->result, $method]);
    }

    /**
     * Checks if current position is valid
     */
    public function valid()
    {
        return $this->key() !== null;
    }

    abstract protected function getMethodForCurrent();
    abstract protected function getMethodForNext();
    abstract protected function getMethodForReset();
}

// vim: syntax=php sw=4 ts=4 et:
