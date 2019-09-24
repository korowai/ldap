<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/AbstractCompareQuery.php
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
abstract class AbstractCompareQuery implements CompareQueryInterface
{
    /** @var string */
    protected $dn;
    /** @var string */
    protected $attribute;
    /** @var string */
    protected $value;
    /** @var bool */
    protected $result = null;


    /**
     * Constructs AbstractCompareQuery
     *
     * @param string $dn
     * @param string $attribute
     * @param string $value
     */
    public function __construct(string $dn, string $attribute, string $value)
    {
        $this->dn = $dn;
        $this->attribute = $attribute;
        $this->value = $value;
    }

    /**
     * Returns ``$dn`` provided to ``__construct()`` at creation time
     * @return string
     */
    public function getDn() : string
    {
        return $this->dn;
    }

    /**
     * Returns ``$attribute`` provided to ``__construct()`` at creation time
     * @return string
     */
    public function getAttribute() : string
    {
        return $this->attribute;
    }

    /**
     * Returns ``$value`` provided to ``__construct()`` at creation time.
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult() : bool
    {
        if (!isset($this->result)) {
            return $this->execute();
        }
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function execute() : bool
    {
        $this->result = $this->doExecuteQuery();
        return $this->result;
    }

    /**
     * Executes query and returns result
     *
     * This method should be implemented in subclass.
     *
     * @return ResultInterface Result of the query.
     */
    abstract protected function doExecuteQuery() : bool;
}

// vim: syntax=php sw=4 ts=4 et:
