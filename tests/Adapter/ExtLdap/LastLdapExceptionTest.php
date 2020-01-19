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
use Korowai\Lib\Ldap\Adapter\ExtLdap\LastLdapException;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class UnitLastLdapException extends TestCase
{
    use LastLdapException;
    use \phpmock\phpunit\PHPMock;

    public function getLdapFunctionMock(...$args)
    {
        return $this->getFunctionMock('\\Korowai\\Lib\\Ldap\\Adapter\ExtLdap', ...$args);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_lastLdapException()
    {
        $link = $this->createMock(LdapLink::class);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(123);
        $this->getLdapFunctionMock("ldap_err2str")
             ->expects($this->once())
             ->with(123)
             ->willReturn("Error message");

        $e = static::lastLdapException($link);
        $this->assertInstanceOf(LdapException::class, $e);
        $this->assertEquals("Error message", $e->getMessage());
        $this->assertEquals(123, $e->getCode());
    }
}

// vim: syntax=php sw=4 ts=4 et:
