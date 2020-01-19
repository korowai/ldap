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

use Korowai\Lib\Ldap\Adapter\ResultAttributeIteratorInterface;

/**
 * Iterates through attributes of a result entry.
 *
 * Only one instance of ``ResultAttributeIterator`` should be used for a given
 * ``ResultEntry``. The internal state (position) of the iterator is
 * keept and managed by the ``ResultEntry`` (provided as ``$entry`` argument to
 * ``ResultAttributeIterator::__construct()``).
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultAttributeIterator implements ResultAttributeIteratorInterface
{
    /** @var ResultEntry */
    private $entry;

    /**
     * Initializes the ``ResultAttributeIterator``.
     *
     * @param ResultEntry $entry An entry containing the attributes.
     */
    public function __construct(ResultEntry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * Returns the ``$entry`` provided to ``__construct`` at creation time.
     * @eturn ResultEntry The ``$entry`` provided to ``__construct`` at creation time.
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * Returns an array of values of the current attribute.
     *
     * Should only be used on valid iterator.
     *
     * @return array an array of values of the current attribute.
     * @link http://php.net/manual/en/iterator.current.php Iterator::current
     */
    public function current()
    {
        return $this->entry->attributes_current();
    }

    /**
     * Returns the key of the current element (name of current attribute).
     * @return string|null The name of current attribute or ``null`` if the
     *         iterator is invalid (past the end).
     *
     * @link http://php.net/manual/en/iterator.key.php Iterator::key
     */
    public function key()
    {
        $key = $this->entry->attributes_key();
        return is_string($key) ? strtolower($key) : $key;
    }

    /**
     * Moves the current position to the next element
     *
     * @link http://php.net/manual/en/iterator.next.php Iterator::next
     */
    public function next()
    {
        $this->entry->attributes_next();
    }

    /**
     * Rewinds back to the first element of the iterator
     *
     * @link http://php.net/manual/en/iterator.rewind.php Iterator::rewind
     */
    public function rewind()
    {
        $this->entry->attributes_reset();
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php Iterator::valid
     */
    public function valid()
    {
        return $this->entry->attributes_key() !== null;
    }
}

// vim: syntax=php sw=4 ts=4 et:
