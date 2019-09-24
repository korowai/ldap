<?php
/**
 * @file src/Korowai/Lib/Ldap/Entry.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

use Korowai\Lib\Ldap\Exception\AttributeException;

/**
 * Represents single ldap entry with DN and attributes
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Entry implements EntryInterface
{
    private $dn;
    private $attributes;

    /**
     * Entry's constructor.
     *
     * @throws \TypeError
     */
    public function __construct(string $dn, array $attributes = array())
    {
        $this->setDn($dn);
        $this->validateAttributes($attributes);
        $this->attributes = $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getDn() : string
    {
        return $this->dn;
    }

    /**
     * {@inheritdoc}
     */
    public function setDn(string $dn)
    {
        $this->validateDn($dn);
        $this->dn = $dn;
    }

    /**
     * Validates string provided as DN.
     *
     * @param string $dn
     * @throws \TypeError
     */
    public function validateDn(string $dn)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute(string $name) : array
    {
        $this->ensureAttributeExists($name);
        return $this->attributes[$name];
    }

    /**
     * Throws AttributeException if given attribute does not exist
     *
     * @throws AttributeException
     */
    public function ensureAttributeExists(string $name)
    {
        if (!$this->hasAttribute($name)) {
            $msg = "Entry '" . $this->dn . "' has no attribute '". $name ."'";
            throw new AttributeException($msg);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasAttribute(string $name) : bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function setAttributes(array $attributes)
    {
        $this->validateAttributes($attributes);
        foreach ($attributes as $name => $values) {
            $this->attributes[$name] = $values;
        }
    }

    /**
     * Check if the given array of attributes can be safely assigned to entry.
     *
     * If not, an exception is thrown.
     *
     * @throws \TypeError
     */
    public function validateAttributes(array $attributes)
    {
        foreach ($attributes as $name => $values) {
            $this->validateAttribute($name, $values);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute(string $name, array $values)
    {
        $this->validateAttribute($name, $values);
        $this->attributes[$name] = $values;
    }

    /**
     * Currently only check the types of attribute name and values
     *
     * @throws \TypeError
     */
    public function validateAttribute(string $name, array $values)
    {
    }
}

// vim: syntax=php sw=4 ts=4 et:
