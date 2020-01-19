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
use Korowai\Lib\Ldap\Adapter\ExtLdap\EntryManager;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;
use Korowai\Lib\Ldap\Entry;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class EntryManagerTest extends TestCase
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

    public function test__construct()
    {
        $link = $this->createMock(LdapLink::class);
        $mngr = new EntryManager($link);
        $this->assertTrue(true); // didn't blow up
    }

    public function test__getLink()
    {
        $link = $this->createMock(LdapLink::class);
        $mngr = new EntryManager($link);
        $this->assertSame($link, $mngr->getLink());
    }

    public function test__add()
    {
        $attributes = ['attr1' => ['attr1val1']];
        $entry = new Entry('dc=korowai,dc=org', $attributes);

        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('add')
             ->with('dc=korowai,dc=org', $attributes)
             ->willReturn(true);

        $mngr = new EntryManager($link);
        $this->assertNull($mngr->add($entry));
    }

    public function test__add__UninitializedLink()
    {
        $attributes = ['attr1' => ['attr1val1']];
        $entry = new Entry('dc=korowai,dc=org', $attributes);

        $link = $this->createLdapLinkMock(false);
        $link->expects($this->never())
             ->method('add');

        $mngr = new EntryManager($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(-1);
        $this->expectExceptionMessage('Uninitialized LDAP link');

        $mngr->add($entry);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__add__Failure()
    {
        $attributes = ['attr1' => ['attr1val1']];
        $entry = new Entry('dc=korowai,dc=org', $attributes);

        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('add')
             ->with('dc=korowai,dc=org', $attributes)
             ->willReturn(false);
        $link->method('errno')
             ->willReturn(2);

        $this->getLdapFunctionMock('ldap_err2str')
             ->expects($this->once())
             ->with(2)
             ->willReturn("Error message");

        $mngr = new EntryManager($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(2);
        $this->expectExceptionMessage('Error message');

        $mngr->add($entry);
    }

    public function test__update()
    {
        $attributes = ['attr1' => ['attr1val1']];
        $entry = new Entry('dc=korowai,dc=org', $attributes);

        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('modify')
             ->with('dc=korowai,dc=org', $attributes)
             ->willReturn(true);

        $mngr = new EntryManager($link);
        $this->assertNull($mngr->update($entry));
    }

    public function test__update__Invalid()
    {
        $attributes = ['attr1' => ['attr1val1']];
        $entry = new Entry('dc=korowai,dc=org', $attributes);

        $link = $this->createLdapLinkMock(false);
        $link->expects($this->never())
             ->method('modify');

        $mngr = new EntryManager($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(-1);
        $this->expectExceptionMessage('Uninitialized LDAP link');

        $mngr->update($entry);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__update__Failure()
    {
        $attributes = ['attr1' => ['attr1val1']];
        $entry = new Entry('dc=korowai,dc=org', $attributes);

        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('modify')
             ->with('dc=korowai,dc=org', $attributes)
             ->willReturn(false);
        $link->method('errno')
             ->willReturn(2);

        $this->getLdapFunctionMock('ldap_err2str')
             ->expects($this->once())
             ->with(2)
             ->willReturn("Error message");

        $mngr = new EntryManager($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(2);
        $this->expectExceptionMessage('Error message');

        $mngr->update($entry);
    }

    public function test__rename__Default()
    {
        $entry = new Entry('dc=korowai,dc=org');

        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('rename')
             ->with('dc=korowai,dc=org', 'cn=korowai', null, true)
             ->willReturn(true);

        $mngr = new EntryManager($link);
        $this->assertNull($mngr->rename($entry, 'cn=korowai'));
    }

    public function test__rename__DeleteOldRdn()
    {
        $entry = new Entry('dc=korowai,dc=org');

        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('rename')
             ->with('dc=korowai,dc=org', 'cn=korowai', null, true)
             ->willReturn(true);

        $mngr = new EntryManager($link);
        $this->assertNull($mngr->rename($entry, 'cn=korowai', true));
    }

    public function test__rename__LeaveOldRdn()
    {
        $entry = new Entry('dc=korowai,dc=org');

        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('rename')
             ->with('dc=korowai,dc=org', 'cn=korowai', null, false)
             ->willReturn(true);

        $mngr = new EntryManager($link);
        $this->assertNull($mngr->rename($entry, 'cn=korowai', false));
    }

    public function test__rename__Invalid()
    {
        $entry = new Entry('dc=korowai,dc=org');

        $link = $this->createLdapLinkMock(false);
        $link->expects($this->never())
             ->method('rename');

        $mngr = new EntryManager($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(-1);
        $this->expectExceptionMessage('Uninitialized LDAP link');

        $mngr->rename($entry, 'cn=korowai', true);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__rename__Failure()
    {
        $entry = new Entry('dc=korowai,dc=org');

        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('rename')
             ->with('dc=korowai,dc=org', 'cn=korowai', null, true)
             ->willReturn(false);
        $link->method('errno')
             ->willReturn(2);

        $this->getLdapFunctionMock('ldap_err2str')
             ->expects($this->once())
             ->with(2)
             ->willReturn("Error message");

        $mngr = new EntryManager($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(2);
        $this->expectExceptionMessage('Error message');

        $mngr->rename($entry, 'cn=korowai', true);
    }

    public function test__delete()
    {
        $entry = new Entry('dc=korowai,dc=org');

        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('delete')
             ->with('dc=korowai,dc=org')
             ->willReturn(true);

        $mngr = new EntryManager($link);
        $this->assertNull($mngr->delete($entry));
    }

    public function test__delete__Invalid()
    {
        $entry = new Entry('dc=korowai,dc=org');

        $link = $this->createLdapLinkMock(false);
        $link->expects($this->never())
             ->method('delete');

        $mngr = new EntryManager($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(-1);
        $this->expectExceptionMessage('Uninitialized LDAP link');

        $mngr->delete($entry);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__delete__Failure()
    {
        $entry = new Entry('dc=korowai,dc=org');

        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('delete')
             ->with('dc=korowai,dc=org')
             ->willReturn(false);
        $link->method('errno')
             ->willReturn(2);

        $this->getLdapFunctionMock('ldap_err2str')
             ->expects($this->once())
             ->with(2)
             ->willReturn("Error message");

        $mngr = new EntryManager($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(2);
        $this->expectExceptionMessage('Error message');

        $mngr->delete($entry);
    }
}

// vim: syntax=php sw=4 ts=4 et:
