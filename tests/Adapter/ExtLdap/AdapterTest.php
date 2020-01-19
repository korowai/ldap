<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Testing\TestCase;
use Korowai\Lib\Ldap\AbstractAdapter;
use Korowai\Lib\Ldap\Adapter\ExtLdap\Adapter;
use Korowai\Lib\Ldap\Adapter\ExtLdap\Binding;
use Korowai\Lib\Ldap\Adapter\ExtLdap\EntryManager;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;
use Korowai\Lib\Ldap\Adapter\ExtLdap\SearchQuery;
use Korowai\Lib\Ldap\Adapter\ExtLdap\CompareQuery;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AdapterTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function getLdapFunctionMock(...$args)
    {
        return $this->getFunctionMock('\\Korowai\\Lib\\Ldap\\Adapter\ExtLdap', ...$args);
    }

    public function test__getBinding()
    {
        $link = $this->createMock(LdapLink::class);

        $adapter = new Adapter($link);

        $bind1 = $adapter->getBinding();
        $bind2 = $adapter->getBinding();
        $this->assertSame($bind1, $bind2);
        $this->assertInstanceOf(Binding::class, $bind1);
    }

    public function test__getEntryManager()
    {
        $link = $this->createMock(LdapLink::class);

        $adapter = new Adapter($link);

        $em1 = $adapter->getEntryManager();
        $em2 = $adapter->getEntryManager();
        $this->assertSame($em1, $em2);
        $this->assertInstanceOf(EntryManager::class, $em1);
    }

    public function test__createSearchQuery()
    {
        $link = $this->createMock(LdapLink::class);

        $adapter = new Adapter($link);

        $query = $adapter->createSearchQuery("dc=korowai,dc=org", "objectClass=*", ['scope' => 'one']);

        $this->assertInstanceOf(SearchQuery::class, $query);
        $this->assertEquals("dc=korowai,dc=org", $query->getBaseDn());
        $this->assertEquals("objectClass=*", $query->getFilter());
        $this->assertEquals('one', $query->getOptions()['scope']);
    }

    public function test__createCompareQuery()
    {
        $link = $this->createMock(LdapLink::class);

        $adapter = new Adapter($link);

        $query = $adapter->createCompareQuery('uid=jsmith,ou=people,dc=example,dc=org', 'userpassword', 'secret');

        $this->assertInstanceOf(CompareQuery::class, $query);
        $this->assertEquals("uid=jsmith,ou=people,dc=example,dc=org", $query->getDn());
        $this->assertEquals("userpassword", $query->getAttribute());
        $this->assertEquals('secret', $query->getValue());
    }
}

// vim: syntax=php sw=4 ts=4 et:
