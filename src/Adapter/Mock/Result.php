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

use Korowai\Lib\Ldap\Adapter\AbstractResult;
use Korowai\Lib\Ldap\Adapter\ResultInterface;
use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface;

/**
 * Wrapper for ldap result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Result extends AbstractResult
{
    /** @var ResultEntry[] */
    private $entries;
    /** @var ResultReference[] */
    private $references;


    /**
     * Returns a Result object made out of *$result* argument.
     *
     * @param  mixed $result an instance of ResultInterface or an array.
     *
     * @return Result
     * @throws \InvalidArgumentException when *$entry* is of wrong type
     */
    public static function make($result)
    {
        if ($result instanceof Result) {
            return $result;
        } elseif ($result instanceof ResultInterface) {
            $entries = $result->getResultEntries();
            $references = $result->getResultReferences();
            return new static($entries, $references);
        } elseif (is_array($result)) {
            return static::createWithArray($result);
        } else {
            $type = gettype($result);
            $type = $type === 'object' ? get_class($result) : $type;
            $msg = 'parameter 1 to Result::make() must be ' .
                   'an instance of ResultInterface or an array, not ' . $type;
            throw new \InvalidArgumentException($msg);
        }
    }

    /**
     * Creates Result instance using an array as argument.
     *
     * The *$result* shall have form:
     *
     *      $result = [
     *          'entries' => [ e1, ... ],
     *          'references' => [ r1, ... ],
     *      ]
     *
     * or
     *
     *      $result = [
     *          e1, ...,
     *          'references' => [ r1, ... ],
     *      ]
     *
     * where ``e1, ...`` are result entries and ``r1, ...`` are result references.
     * Entries ``e1, ...`` and references ``r1, ...``  should be compatible with
     * ``ResultEntry::make`` and ``ResultReference::make`` respectively.  In
     * each form, the ``'references'`` item is optional (result without references).
     *
     * @param  array $result
     * @return Result
     */
    public static function createWithArray(array $result)
    {
        $entries = $result['entries'] ?? array_filter(
            $result,
            function ($item, $key) {
                return is_int($key);
            },
            ARRAY_FILTER_USE_BOTH
        );

        $entries = array_map(function ($entry) {
            return ResultEntry::make($entry);
        }, $entries);

        $references = array_map(function ($reference) {
            return ResultReference::make($reference);
        }, $result['references'] ?? []);

        return new static($entries, $references);
    }

    /**
     * Initializes new ``Result`` instance
     *
     * @param  array $entries
     * @param  array $references
     */
    public function __construct(array $entries, array $references = [])
    {
        $this->entries = $entries;
        $this->references = $references;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultEntries() : array
    {
        return $this->entries;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultReferences() : array
    {
        return $this->references;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultEntryIterator() : ResultEntryIteratorInterface
    {
        return new ResultEntryIterator($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getResultReferenceIterator() : ResultReferenceIteratorInterface
    {
        return new ResultReferenceIterator($this);
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Returns the result of ``current($this->entries)``.
     *
     * @return mixed
     */
    public function entries_current()
    {
        return current($this->entries);
    }

    /**
     * Returns the result of ``$this->entries_current()->getDn()``.
     *
     * @return string|null
     */
    public function entries_key() : ?string
    {
        return  ($current = $this->entries_current()) !== false ? $current->getDn() : null;
    }

    /**
     * Returns the result of ``next($this->entries)``.
     *
     * @return mixed
     */
    public function entries_next()
    {
        return next($this->entries);
    }

    /**
     * Returs the result of ``reset($this->entries)``.
     *
     * @return mixed
     */
    public function entries_reset()
    {
        return reset($this->entries);
    }

    /**
     * Returns the result of ``current($this->references)``.
     *
     * @return mixed
     */
    public function references_current()
    {
        return current($this->references);
    }

    /**
     * Returns the result of ``$this->references_current()->getDn()``.
     *
     * @return string|null
     */
    public function references_key() : ?string
    {
        return  ($current = $this->references_current()) !== false ? $current->getDn() : null;
    }

    /**
     * Returns the result of ``next($this->references)``.
     *
     * @return mixed
     */
    public function references_next()
    {
        return next($this->references);
    }

    /**
     * Returs the result of ``reset($this->references)``.
     *
     * @return mixed
     */
    public function references_reset()
    {
        return reset($this->references);
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
