<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

use Korowai\Testing\TestCase;
use Korowai\Lib\Ldap\Adapter\AbstractAdapterFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractAdapterFactoryTest extends TestCase
{
    public static function getDefaultConfig()
    {
        return [
            'host' => 'localhost',
            'uri'  => 'ldap://localhost',
            'port' => 389,
            'encryption' => 'none',
            'options' => []
        ];
    }

    private function getAbstractAdapterFactoryMock($ctor = true, array $methods = [])
    {
        $builder = $this->getMockBuilder(AbstractAdapterFactory::class);

        if (!$ctor) {
            $builder->disableOriginalConstructor();
        } elseif (is_array($ctor)) {
            $builder->setConstructorArgs($ctor);
        }

        if (!in_array('configureNestedOptionsResolver', $methods)) {
            $methods[] = 'configureNestedOptionsResolver';
        }
        $builder->setMethods($methods);
        return $builder->getMockForAbstractClass();
    }

    public function test_configure_CtorWithConfig()
    {
        $config = ['host' => 'korowai.org'];
        $factory = $this->getAbstractAdapterFactoryMock(false, ['configure']);
        $factory->expects($this->once())
                ->method('configure')
                ->with($config);

        $factory->__construct($config);
    }

    public function test_configure_ConfigureResolvers()
    {
        $resolver = null;
        $nestedResolver = null;

        $factory = $this->getAbstractAdapterFactoryMock(
            true,
            ['configureOptionsResolver']
        );

        $factory->expects($this->once())
                ->method('configureOptionsResolver')
                ->with($this->isInstanceOf(OptionsResolver::class))
                ->willReturnCallback(function (OptionsResolver $r) use (&$resolver) {
                    $resolver = $r;
                });

        $factory->expects($this->once())
                ->method('configureNestedOptionsResolver')
                ->with($this->isInstanceOf(OptionsResolver::class))
                ->willReturnCallback(function (OptionsResolver $r) use (&$nestedResolver) {
                    $nestedResolver = $r;
                });

        $factory->configure([]);
        $expected = ['options' => []];
        $this->assertInstanceOf(OptionsResolver::class, $resolver);
        $this->assertInstanceOf(OptionsResolver::class, $nestedResolver);
        $this->assertNotSame($resolver, $nestedResolver);
        $this->assertEquals($expected, $factory->getConfig());
    }

    public function test_configure_Defaults()
    {
        $factory = $this->getAbstractAdapterFactoryMock();

        $factory->configure([]);

        $expected = $this->getDefaultConfig();
        $this->assertEquals($expected, $factory->getConfig());
    }

    public function test_configure_Host()
    {
        $factory = $this->getAbstractAdapterFactoryMock();

        $factory->configure(['host' => 'korowai.org']);

        $expected = $this->getDefaultConfig();
        $expected['host'] = 'korowai.org';
        $expected['uri'] = 'ldap://korowai.org';
        $this->assertEquals($expected, $factory->getConfig());
    }

    public function test_configure_HostEncryption()
    {
        $factory = $this->getAbstractAdapterFactoryMock();

        $factory->configure(['host' => 'korowai.org', 'encryption' => 'ssl']);

        $expected = $this->getDefaultConfig();
        $expected['host'] = 'korowai.org';
        $expected['encryption'] = 'ssl';
        $expected['uri'] = 'ldaps://korowai.org';
        $expected['port'] = 636;
        $this->assertEquals($expected, $factory->getConfig());
    }

    public function test_configure_HostEncryptionPort()
    {
        $factory = $this->getAbstractAdapterFactoryMock();

        $factory->configure(['host' => 'korowai.org', 'encryption' => 'ssl', 'port' => 123]);

        $expected = $this->getDefaultConfig();
        $expected['host'] = 'korowai.org';
        $expected['encryption'] = 'ssl';
        $expected['uri'] = 'ldaps://korowai.org:123';
        $expected['port'] = 123;
        $this->assertEquals($expected, $factory->getConfig());
    }

    public function test_configure_NestedOptions()
    {
        $factory = $this->getAbstractAdapterFactoryMock();

        $factory->expects($this->once())
                ->method('configureNestedOptionsResolver')
                ->willReturnCallback(function ($resolver) {
                    $resolver->setDefault('protocol_version', 3);
                });

        $factory->configure([]);

        $expected = $this->getDefaultConfig();
        $expected['options']['protocol_version'] = 3;
        $this->assertEquals($expected, $factory->getConfig());
    }
}

// vim: syntax=php sw=4 ts=4 et:
