<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\Adapter\ResultRecordInterface;

/**
 * Common functions for ResultEntry and ResultReference.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultRecord implements ResultRecordInterface
{
    /** @var resource */
    private $record;
    /** @var Result */
    private $result;

    /**
     * Initializes the ``ResultRecord`` instance
     *
     * @param  resource|null $record
     * @param Result $result
     */
    protected function initResultRecord($record, Result $result)
    {
        $this->record = $record;
        $this->result = $result;
    }

    /**
     * Return the underlying resource identifier.
     *
     * @return resource
     */
    public function getResource()
    {
        return $this->record;
    }

    /**
     * Return the Result object which contains the entry.
     *
     * @return resource
     */
    public function getResult()
    {
        return $this->result;
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Get the DN of a result entry
     *
     * @link http://php.net/manual/en/function.ldap-get-dn.php ldap_get_dn()
     */
    public function get_dn()
    {
        return $this->result->getLink()->get_dn($this);
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd

    /**
     * {@inheritdoc}
     */
    public function getDn() : string
    {
        return $this->get_dn();
    }
}

// vim: syntax=php sw=4 ts=4 et:
