<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/ExtLdap/ResultReference.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;
use Korowai\Lib\Ldap\Adapter\ExtLdap\Result;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultAttributeIterator;

/**
 * Wrapper for ldap reference result resource.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReference extends ResultEntry implements ResultReferenceInterface
{
    // @codingStandardsIgnoreStart
    // phpcs:disable Generic.NamingConventions.CamelCapsFunctionName

    /**
     * Get next result reference
     *
     * @link http://php.net/manual/en/function.ldap-next-reference.php ldap_next_reference()
     */
    public function next_reference()
    {
        return $this->getResult()->getLink()->next_reference($this);
    }

    /**
     * Extract referrals from the reference message
     *
     * @link http://php.net/manual/en/function.ldap-parse-reference.php ldap_parse_reference()
     */
    public function parse_reference(&$referrals)
    {
        return $this->getResult()->getLink()->parse_reference($this, $referrals);
    }

    // phpcs:enable Generic.NamingConventions.CamelCapsFunctionName
    // @codingStandardsIgnoreEnd

    /**
     * {@inheritdoc}
     */
    public function getReferrals()
    {
        if (!$this->parse_reference($referrals)) {
            return false;
        }
        return $referrals;
    }
}

// vim: syntax=php sw=4 ts=4 et:
