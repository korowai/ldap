<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

use Korowai\Lib\Ldap\Adapter\ResultInterface;

use \InvalidArgumentException;

/**
 * An abstract base for Ldap class.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractLdap implements LdapInterface
{
    /**
     * Create search query, execute and return its result
     *
     * @param  string $base_dn
     * @param  string $filter
     * @param  array $options
     *
     * @return ResultInterface Query result
     */
    public function search(string $base_dn, string $filter, array $options = []) : ResultInterface
    {
        return $this->createSearchQuery($base_dn, $filter, $options)->getResult();
    }

    /**
     * Create compare query, execute and return its result
     *
     * @param  string $dn
     * @param  string $attribute
     * @param  string $value
     *
     * @return bool Result of the comparison
     */
    public function compare(string $dn, string $attribute, string $value) : bool
    {
        return $this->createCompareQuery($dn, $attribute, $value)->getResult();
    }
}

// vim: syntax=php sw=4 ts=4 et:
