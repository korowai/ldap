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
use Korowai\Lib\Ldap\Adapter\AbstractSearchQuery;
use Korowai\Lib\Ldap\Adapter\ExtLdap\SearchQuery;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;
use Korowai\Lib\Ldap\Adapter\ResultInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class SearchQueryTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function getLdapFunctionMock(...$args)
    {
        return $this->getFunctionMock('\\Korowai\\Lib\\Ldap\\Adapter\ExtLdap', ...$args);
    }

    public function test__extends__AbstractSearchQuery()
    {
        $this->assertExtendsClass(AbstractSearchQuery::class, SearchQuery::class);
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

    public function test__construct()
    {
        $link = $this->createMock(LdapLink::class);
        $query = new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*");
        $this->assertTrue(true); // didn't blow up
    }

    public function test__construct__WithInvalidOptions()
    {
        $link = $this->createLdapLinkMock(true);
        $result = $this->createMock(ResultInterface::class);

        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessageMatches('/option "scope" with value "foo"/');

        new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*", ['scope' => 'foo']);
    }

    public function test__getLink()
    {
        $link = $this->createMock(LdapLink::class);
        $query = new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*");
        $this->assertSame($link, $query->getLink());
    }

    public function test__execute__base()
    {
        $link = $this->createLdapLinkMock(true);
        $result = $this->createMock(ResultInterface::class);
        $query = new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*", ['scope' => 'base']);
        $link->expects($this->exactly(2))
             ->method('read')
             ->with("dc=korowai,dc=org", "objectClass=*", ["*"], 0, 0, 0, LDAP_DEREF_NEVER)
             ->willReturn($result);
        $link->expects($this->never())
             ->method('list');
        $link->expects($this->never())
             ->method('search');
        $this->assertSame($result, $query->execute());
        $this->assertSame($result, $query->execute());
        $this->assertSame($result, $query->getResult());
    }

    public function test__execute__one()
    {
        $link = $this->createLdapLinkMock(true);
        $result = $this->createMock(ResultInterface::class);
        $query = new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*", ['scope' => 'one']);
        $link->expects($this->never())
             ->method('read');
        $link->expects($this->exactly(2))
             ->method('list')
             ->with("dc=korowai,dc=org", "objectClass=*", ["*"], 0, 0, 0, LDAP_DEREF_NEVER)
             ->willReturn($result);
        $link->expects($this->never())
             ->method('search');
        $this->assertSame($result, $query->execute());
        $this->assertSame($result, $query->execute());
        $this->assertSame($result, $query->getResult());
    }

    public function test__execute__sub()
    {
        $link = $this->createLdapLinkMock(true);
        $result = $this->createMock(ResultInterface::class);
        $query = new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*", ['scope' => 'sub']);
        $link->expects($this->never())
             ->method('read');
        $link->expects($this->never())
             ->method('list');
        $link->expects($this->exactly(2))
             ->method('search')
             ->with("dc=korowai,dc=org", "objectClass=*", ["*"], 0, 0, 0, LDAP_DEREF_NEVER)
             ->willReturn($result);
        $this->assertSame($result, $query->execute());
        $this->assertSame($result, $query->execute());
        $this->assertSame($result, $query->getResult());
    }

    public function test__execute__default_scope()
    {
        $link = $this->createLdapLinkMock(true);
        $result = $this->createMock(ResultInterface::class);
        $query = new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*");
        $link->expects($this->never())
             ->method('read');
        $link->expects($this->never())
             ->method('list');
        $link->expects($this->exactly(2))
             ->method('search')
             ->with("dc=korowai,dc=org", "objectClass=*", ["*"], 0, 0, 0, LDAP_DEREF_NEVER)
             ->willReturn($result);
        $this->assertSame($result, $query->execute());
        $this->assertSame($result, $query->execute());
        $this->assertSame($result, $query->getResult());
    }

    public function test__execute__sub_WithoutDeref()
    {
        $link = $this->createLdapLinkMock(true);
        $result = $this->createMock(ResultInterface::class);

        $query = $this->getMockBuilder(SearchQuery::class)
                      ->disableOriginalConstructor()
                      ->setMethods(['getOptions', 'getLink', 'getBaseDn', 'getFilter'])
                      ->getMock();

        $query->expects($this->any())
               ->method('getBaseDn')
               ->with()
               ->willReturn("dc=korowai,dc=org");
        $query->expects($this->any())
               ->method('getFilter')
               ->with()
               ->willReturn("objectClass=*");
        $query->expects($this->any())
               ->method('getOptions')
               ->with()
               ->willReturn(['attributes' => ['*'], 'attrsOnly' => 0, 'sizeLimit' => 0, 'timeLimit' => 0]);
        $query->expects($this->any())
               ->method('getLink')
               ->with()
               ->willReturn($link);

        $link->expects($this->never())
             ->method('read');
        $link->expects($this->never())
             ->method('list');
        $link->expects($this->exactly(2))
             ->method('search')
             ->with("dc=korowai,dc=org", "objectClass=*", ["*"], 0, 0, 0, LDAP_DEREF_NEVER)
             ->willReturn($result);

        $this->assertSame($result, $query->execute());
        $this->assertSame($result, $query->execute());
        $this->assertSame($result, $query->getResult());
    }

    public function test__execute__InvalidScope()
    {
        $link = $this->createLdapLinkMock(true);

        $query = $this->getMockBuilder(SearchQuery::class)
                      ->disableOriginalConstructor()
                      ->setMethods(['getOptions'])
                      ->getMock();

        $query->expects($this->once())
               ->method('getOptions')
               ->with()
               ->willReturn(['scope' => 'foo']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unsupported search scope "foo"');

        $query->execute();
    }

    public function test__execute__UninitializedLink()
    {
        $link = $this->createLdapLinkMock(false);
        $result = $this->createMock(ResultInterface::class);
        $query = new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*", ['scope' => 'sub']);
        $link->expects($this->never())
             ->method('read');
        $link->expects($this->never())
             ->method('list');
        $link->expects($this->never())
             ->method('search');

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
        $query = new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*", ['scope' => 'sub']);
        $link->expects($this->never())
             ->method('read');
        $link->expects($this->never())
             ->method('list');
        $link->expects($this->once())
             ->method('search')
             ->with("dc=korowai,dc=org", "objectClass=*", ["*"], 0, 0, 0, LDAP_DEREF_NEVER)
             ->willReturn(false);

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

    public function test__getResult__base()
    {
        $link = $this->createLdapLinkMock(true);
        $result = $this->createMock(ResultInterface::class);
        $query = new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*", ['scope' => 'base']);
        $link->expects($this->once())
             ->method('read')
             ->with("dc=korowai,dc=org", "objectClass=*", ["*"], 0, 0, 0, LDAP_DEREF_NEVER)
             ->willReturn($result);
        $link->expects($this->never())
             ->method('list');
        $link->expects($this->never())
             ->method('search');
        $this->assertSame($result, $query->getResult());
        $this->assertSame($result, $query->getResult());
    }

    public function test__getResult__one()
    {
        $link = $this->createLdapLinkMock(true);
        $result = $this->createMock(ResultInterface::class);
        $query = new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*", ['scope' => 'one']);
        $link->expects($this->never())
             ->method('read');
        $link->expects($this->once())
             ->method('list')
             ->with("dc=korowai,dc=org", "objectClass=*", ["*"], 0, 0, 0, LDAP_DEREF_NEVER)
             ->willReturn($result);
        $link->expects($this->never())
             ->method('search');
        $this->assertSame($result, $query->getResult());
        $this->assertSame($result, $query->getResult());
    }

    public function test__getResult__sub()
    {
        $link = $this->createLdapLinkMock(true);
        $result = $this->createMock(ResultInterface::class);
        $query = new SearchQuery($link, "dc=korowai,dc=org", "objectClass=*", ['scope' => 'sub']);
        $link->expects($this->never())
             ->method('read');
        $link->expects($this->never())
             ->method('list');
        $link->expects($this->once())
             ->method('search')
             ->with("dc=korowai,dc=org", "objectClass=*", ["*"], 0, 0, 0, LDAP_DEREF_NEVER)
             ->willReturn($result);
        $this->assertSame($result, $query->getResult());
        $this->assertSame($result, $query->getResult());
    }
}

// vim: syntax=php sw=4 ts=4 et:
