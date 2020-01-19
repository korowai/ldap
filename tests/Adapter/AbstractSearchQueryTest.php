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
use Korowai\Lib\Ldap\Adapter\AbstractSearchQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractSearchQueryTest extends TestCase
{
    public static function getDefaultOptions()
    {
        return [
            'scope' => 'sub',
            'attributes' => '*',
            'attrsOnly' => 0,
            'deref' => 'never',
            'sizeLimit' => 0,
            'timeLimit' => 0
        ];
    }

    public static function getDefaultOptionsResolved()
    {
        return [
            'scope' => 'sub',
            'attributes' => ['*'],
            'attrsOnly' => 0,
            'deref' => 'never',
            'sizeLimit' => 0,
            'timeLimit' => 0
        ];
    }

    private function getAbstractSearchQueryMock($ctor = true, array $methods = [])
    {
        $builder = $this->getMockBuilder(AbstractSearchQuery::class);

        if (!$ctor) {
            $builder->disableOriginalConstructor();
        } elseif (is_array($ctor)) {
            $builder->setConstructorArgs($ctor);
        }

        if (!in_array('doExecuteQuery', $methods)) {
            $methods[] = 'doExecuteQuery';
        }
        $builder->setMethods($methods);
        return $builder->getMockForAbstractClass();
    }

    public function test__getDefaultOptions()
    {
        $expected = static::getDefaultOptions();
        $this->assertEquals($expected, AbstractSearchQuery::getDefaultOptions());
    }

    public function test__defaultOptions()
    {
        $query = $this->getAbstractSearchQueryMock(["dc=korowai,dc=org", "objectClass=*"]);
        $expected = static::getDefaultOptionsResolved();
        $this->assertEquals($expected, $query->getOptions());
    }

    public function test__scope()
    {
        $scopes = ['base', 'one', 'sub'];

        foreach ($scopes as $scope) {
            $query = $this->getAbstractSearchQueryMock(
                ["dc=korowai,dc=org", "objectClass=*",
                ['scope' => $scope]]
            );
            $this->assertEquals($scope, $query->getOptions()['scope']);
        }
    }

    public function test__scope_Invalid()
    {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "scope" with value "foo" is invalid.');
        $query = $this->getAbstractSearchQueryMock(
            ["dc=korowai,dc=org", "objectClass=*",
            ['scope' => 'foo']]
        );
    }

    public function test__deref()
    {
        $scopes = ['always', 'never', 'finding', 'searching'];

        foreach ($scopes as $deref) {
            $query = $this->getAbstractSearchQueryMock(
                ["dc=korowai,dc=org", "objectClass=*",
                ['deref' => $deref]]
            );
            $this->assertEquals($deref, $query->getOptions()['deref']);
        }
    }

    public function test__deref_Invalid()
    {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "deref" with value "foo" is invalid.');
        $query = $this->getAbstractSearchQueryMock(
            ["dc=korowai,dc=org", "objectClass=*",
            ['deref' => 'foo']]
        );
    }

    public function test__attributes()
    {
        $query = $this->getAbstractSearchQueryMock(
            ["dc=korowai,dc=org", "objectClass=*",
            ['attributes' => 'foo']]
        );
        $this->assertEquals(['foo'], $query->getOptions()['attributes']);
    }

    public function test__getBaseDn()
    {
        $query = $this->getAbstractSearchQueryMock(
            ["dc=korowai,dc=org", "objectClass=*"]
        );

        $this->assertEquals("dc=korowai,dc=org", $query->getBaseDn());
    }

    public function test__getFilter()
    {
        $query = $this->getAbstractSearchQueryMock(
            ["dc=korowai,dc=org", "objectClass=*"]
        );
        $this->assertEquals("objectClass=*", $query->getFilter());
    }

    public function test__getResult()
    {
        $query = $this->getAbstractSearchQueryMock(
            ["dc=korowai,dc=org", "objectClass=*"]
        );
        $query->expects($this->once())
              ->method('doExecuteQuery')
              ->with(); // XXX: ResultInterface mock not necessary?
        $query->getResult();
        $query->getResult();
    }

    public function test__execute()
    {
        $query = $this->getAbstractSearchQueryMock(
            ["dc=korowai,dc=org", "objectClass=*"]
        );
        $query->expects($this->exactly(2))
              ->method('doExecuteQuery')
              ->with(); // XXX: ResultInterface mock not necessary?
        $query->execute();
        $query->execute();
    }
}

// vim: syntax=php sw=4 ts=4 et:
