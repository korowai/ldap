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

use Korowai\Lib\Ldap\Adapter\ResultEntryToEntry;
use Korowai\Lib\Ldap\Adapter\ResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\ResultAttributeIteratorInterface;

/**
 * A single entry in LDAP search result.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultEntry extends AbstractResultRecord implements ResultEntryInterface
{
    use ResultEntryToEntry;

    /** @var array */
    private $attributes;
    /** @var ResultAttributeIterator */
    private $iterator;

    /**
     * Returns ``ResultEntryInterface::class``.
     *
     * @return string
     */
    public static function getInterfaceName() : string
    {
        return ResultEntryInterface::class;
    }

    /**
     * Creates ResultEntry instance taking dn and attributes from another
     * instance of ResultEntryInterface.
     *
     * @param  ResultEntryInterface $entry
     * @return ResultEntry
     */
    public static function createWithInterface($entry)
    {
        return new static($entry->getDn(), $entry->getAttributes());
    }

    /**
     * Creates ResultEntry instance using an array as argument.
     *
     * The *$entry* shall have form:
     *
     *      $entry = [
     *          'dn' => '...',
     *          'attributes' => [
     *              'attr1' => [...],
     *              ...
     *          ]
     *      ]
     *
     * or just:
     *
     *      $entry = [
     *          'dn' => '...',
     *          'attr1' => [...],
     *          ...
     *      ]
     *
     * @param  array $entry
     * @return ResultEntry
     */
    public static function createWithArray(array $entry)
    {
        $dn = $entry['dn'];

        if (($attributes = $entry['attributes'] ?? null) === null) {
            // filter-out non-attribute items
            $attributes = array_filter($entry, function ($item, $key) {
                return strtolower($key) !== 'dn';
            }, ARRAY_FILTER_USE_BOTH);
        }

        // convert non-array attributes to arrays
        $attributes = array_map(function ($value) {
            return is_array($value) ? $value : [ $value ];
        }, $attributes);

        return new static($dn, $attributes);
    }

    /**
     * Initializes the ``ResultEntry`` instance
     *
     * @param  string $dn
     * @param  array $attributes
     */
    public function __construct(string $dn, array $attributes = [])
    {
        $this->initResultRecord($dn);
        $this->attributes = $attributes;
    }

    /**
     * It always returns same instance. When used for the first
     * time, the iterator is set to point to the first attribute of the entry.
     * For subsequent calls, the method just return the iterator without
     * altering its position.
     */
    public function getAttributeIterator() : ResultAttributeIteratorInterface
    {
        if (!isset($this->iterator)) {
            $this->iterator = new ResultAttributeIterator($this);
        }
        return $this->iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes() : array
    {
        return array_change_key_case($this->attributes, CASE_LOWER);
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Returns the result of ``current($this->attributes)``.
     */
    public function attributes_current()
    {
        return current($this->attributes);
    }

    /**
     * Returns the result of ``key($this->attributes)``.
     */
    public function attributes_key()
    {
        return key($this->attributes);
    }

    /**
     * Returns the result of ``next($this->attributes)``.
     */
    public function attributes_next()
    {
        return next($this->attributes);
    }

    /**
     * Returs the result of ``reset($this->attributes)``.
     */
    public function attributes_reset()
    {
        return reset($this->attributes);
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
