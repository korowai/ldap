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
use Korowai\Lib\Ldap\Adapter\AbstractCompareQuery;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractCompareQueryTest extends TestCase
{
    private function getAbstractCompareQueryMock($ctor = true, array $methods = [])
    {
        $builder = $this->getMockBuilder(AbstractCompareQuery::class);

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

    public function test__getDn()
    {
        $query = $this->getAbstractCompareQueryMock(
            ["uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret"]
        );

        $this->assertEquals("uid=jsmith,ou=people,dc=example,dc=org", $query->getDn());
    }

    public function test__getAttribute()
    {
        $query = $this->getAbstractCompareQueryMock(
            ["uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret"]
        );
        $this->assertEquals("userpassword", $query->getAttribute());
    }

    public function test__getValue()
    {
        $query = $this->getAbstractCompareQueryMock(
            ["uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret"]
        );
        $this->assertEquals("secret", $query->getValue());
    }

    public function test__getResult()
    {
        $query = $this->getAbstractCompareQueryMock(
            ["uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret"]
        );
        $query->expects($this->once())
              ->method('doExecuteQuery')
              ->with(); // XXX: ResultInterface mock not necessary?
        $query->getResult();
        $query->getResult();
    }

    public function test__execute()
    {
        $query = $this->getAbstractCompareQueryMock(
            ["uid=jsmith,ou=people,dc=example,dc=org", "userpassword", "secret"]
        );
        $query->expects($this->exactly(2))
              ->method('doExecuteQuery')
              ->with(); // XXX: ResultInterface mock not necessary?
        $query->execute();
        $query->execute();
    }
}

// vim: syntax=php sw=4 ts=4 et:
