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
use Korowai\Lib\Ldap\Adapter\AbstractCompareQuery;
use Korowai\Lib\Ldap\Adapter\ExtLdap\CompareQuery;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CompareQueryTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function getLdapFunctionMock(...$args)
    {
        return $this->getFunctionMock('\\Korowai\\Lib\\Ldap\\Adapter\ExtLdap', ...$args);
    }

    public function createLdapLinkMock($valid, $unbind = true)
    {
        $link = $this->createMock(LdapLink::class);
        if ($valid === true || $valid === false) {
            $link->method('isValid')->willReturn($valid);
        }
        if ($unbind === true || $unbind === false) {
            $link->method('unbind')->willReturn($unbind);
        }
        return $link;
    }

    public function test__extends__AbstractCompareQuery()
    {
        $this->assertExtendsClass(AbstractCompareQuery::class, CompareQuery::class);
    }

    public function test__construct()
    {
        $link = $this->createMock(LdapLink::class);
        $query = new CompareQuery($link, "uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret");
        $this->assertTrue(true); // didn't blow up
    }

    public function test__getLink()
    {
        $link = $this->createMock(LdapLink::class);
        $query = new CompareQuery($link, "uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret");
        $this->assertSame($link, $query->getLink());
    }

    public function test__execute__true()
    {
        $link = $this->createLdapLinkMock(true);
        $query = new CompareQuery($link, "uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret");
        $link->expects($this->exactly(2))
             ->method('compare')
             ->with("uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret")
             ->willReturn(true);
        $this->assertTrue($query->execute());
        $this->assertTrue($query->execute());
    }

    public function test__execute__false()
    {
        $link = $this->createLdapLinkMock(true);
        $query = new CompareQuery($link, "uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "wrongpass");
        $link->expects($this->exactly(2))
             ->method('compare')
             ->with("uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "wrongpass")
             ->willReturn(false);
        $this->assertFalse($query->execute());
        $this->assertFalse($query->execute());
    }

    public function test__execute__UninitializedLink()
    {
        $link = $this->createLdapLinkMock(false);
        $query = new CompareQuery($link, "uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret");
        $link->expects($this->never())
             ->method('compare');

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(-1);
        $this->expectExceptionMessage('Uninitialized LDAP link');

        $query->execute();
    }

    /**
     * @runInSeparateProcess
     */
    public function test__execute__LdapError()
    {
        $link = $this->createLdapLinkMock(true);
        $query = new CompareQuery($link, "uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret");
        $link->expects($this->once())
             ->method('compare')
             ->with("uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret")
             ->willReturn(-1);

        $link->method('errno')
             ->willReturn(2);
        $this->getLdapFunctionMock('ldap_err2str')
             ->expects($this->once())
             ->with(2)
             ->willReturn("Error message");

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(2);
        $this->expectExceptionMessage('Error message');

        $query->execute();
    }

    public function test__getResult__true()
    {
        $link = $this->createLdapLinkMock(true);
        $query = new CompareQuery($link, "uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret");
        $link->expects($this->once())
             ->method('compare')
             ->with("uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret")
             ->willReturn(true);
        $this->assertTrue($query->getResult());
        $this->assertTrue($query->getResult());
    }

    public function test__getResult__false()
    {
        $link = $this->createLdapLinkMock(true);
        $query = new CompareQuery($link, "uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret");
        $link->expects($this->once())
             ->method('compare')
             ->with("uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret")
             ->willReturn(false);
        $this->assertFalse($query->getResult());
        $this->assertFalse($query->getResult());
    }
}

// vim: syntax=php sw=4 ts=4 et:
