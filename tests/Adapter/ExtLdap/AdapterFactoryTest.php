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
use Korowai\Lib\Ldap\Adapter\ExtLdap\Adapter;
use Korowai\Lib\Ldap\Adapter\ExtLdap\AdapterFactory;
use Korowai\Lib\Ldap\Adapter\AbstractAdapterFactory;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AdapterFactoryTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function getLdapFunctionMock(...$args)
    {
        return $this->getFunctionMock('\\Korowai\\Lib\\Ldap\\Adapter\ExtLdap', ...$args);
    }

    /**
     * @runInSeparateProcess
     */
    public function test_construct_ExtLdapNotLoaded()
    {
        $this->getLdapFunctionMock('extension_loaded')
             ->expects($this->once())
             ->with('ldap')
             ->willReturn(false);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionMessage('The LDAP PHP extension is not enabled');
        $this->expectExceptionCode(-1);

        new AdapterFactory();
    }


    public function test_createAdapter_ConnectFailure_1()
    {
        $this->getLdapFunctionMock("ldap_connect")
             ->expects($this->once())
             ->with('ldap://localhost')
             ->willReturnCallback(
                 function (...$args) {
                     trigger_error('Error message');
                 }
             );

        $this->getLdapFunctionMock("ldap_set_option")
             ->expects($this->never());

        $factory = new AdapterFactory;
        $factory->configure([]);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionMessage('Error message');
        $this->expectExceptionCode(-1);

        $factory->createAdapter();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_createAdapter_ConnectFailure_2()
    {
        $this->getLdapFunctionMock("ldap_connect")
             ->expects($this->once())
             ->with('ldap://localhost')
             ->willReturn(null);

        $this->getLdapFunctionMock("ldap_set_option")
             ->expects($this->never());

        $factory = new AdapterFactory;
        $factory->configure([]);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionMessage('Failed to create LDAP connection');
        $this->expectExceptionCode(-1);

        $factory->createAdapter();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_createAdapter_SetOptionFailure()
    {
        $this->getLdapFunctionMock("ldap_connect")
             ->expects($this->once())
             ->with('ldap://localhost')
             ->willReturnCallback(function (...$args) {
                 return \ldap_connect(...$args);
             });

        $this->getLdapFunctionMock("ldap_set_option")
             ->expects($this->once())
             ->willReturn(false);

        $this->getLdapFunctionMock("ldap_errno")
             ->expects($this->once())
             ->with($this->callback('is_resource'))
             ->willReturn(123);

        $this->getLdapFunctionMock("ldap_err2str")
             ->expects($this->once())
             ->with(123)
             ->willReturn("Error message");

        $factory = new AdapterFactory;
        $factory->configure([]);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionMessage('Error message');
        $this->expectExceptionCode(123);

        $factory->createAdapter();
    }

    public function test_createAdapter()
    {
        $factory = new AdapterFactory;
        $factory->configure([]);
        $adapter = $factory->createAdapter();
        $this->assertInstanceOf(Adapter::class, $adapter);
        $this->assertTrue($adapter->getLdapLink()->isValid());
    }
}

// vim: syntax=php sw=4 ts=4 et:
