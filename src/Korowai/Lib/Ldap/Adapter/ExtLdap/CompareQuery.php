<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/ExtLdap/CompareQuery.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\Adapter\AbstractCompareQuery;
use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\emptyErrorHandler;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CompareQuery extends AbstractCompareQuery
{
    use EnsureLdapLink;
    use LastLdapException;

    /** @var LdapLink */
    private $link;

    /**
     * Constructs CompareQuery
     */
    public function __construct(LdapLink $link, string $dn, string $attribute, string $value)
    {
        $this->link  = $link;
        parent::__construct($dn, $attribute, $value);
    }

    /**
     * Returns a link resource
     *
     * @return resource
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteQuery() : bool
    {
        static::ensureLdapLink($this->getLink());
        return with(emptyErrorHandler())(function ($eh) {
            return $this->doExecuteQueryImpl();
        });
    }

    private function doExecuteQueryImpl() : bool
    {
        $result = $this->getLink()->compare($this->dn, $this->attribute, $this->value);
        if (-1 === $result) {
            throw static::lastLdapException($this->getLink());
        }
        return $result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
