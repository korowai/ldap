<?php
/**
 * @file Tests/AbstractLdapTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Tests;

use PHPUnit\Framework\TestCase;
use Korowai\Lib\Ldap\AbstractLdap;
use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\Adapter\SearchQueryInterface;
use Korowai\Lib\Ldap\Adapter\CompareQueryInterface;
use Korowai\Lib\Ldap\Adapter\ResultInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractLdapTest extends TestCase
{
    public function test__implements__LdapInterface()
    {
        $interfaces = class_implements(AbstractLdap::class);
        $this->assertContains(LdapInterface::class, $interfaces);
    }

    public function test__search()
    {
        $result = $this->getMockBuilder(ResultInterface::class)
                       ->getMockForAbstractClass();

        $query = $this->getMockBuilder(SearchQueryInterface::class)
                      ->setMethods(['getResult'])
                      ->getMockForAbstractClass();

        $query->expects($this->once())
              ->method('getResult')
              ->with()
              ->willReturn($result);

        $ldap = $this->getMockBuilder(AbstractLdap::class)
                     ->setMethods(['createSearchQuery'])
                     ->getMockForAbstractClass();

        $args = [ 'dc=example,dc=org', '(objectClass=*)', ['foo'] ];
        $ldap->expects($this->once())
             ->method('createSearchQuery')
             ->with(...$args)
             ->willReturn($query);

        $this->assertSame($result, $ldap->search(...$args));
    }

    public function test__compare()
    {
        $query = $this->getMockBuilder(CompareQueryInterface::class)
                      ->setMethods(['getResult'])
                      ->getMockForAbstractClass();

        $query->expects($this->once())
              ->method('getResult')
              ->with()
              ->willReturn(true);

        $ldap = $this->getMockBuilder(AbstractLdap::class)
                     ->setMethods(['createCompareQuery'])
                     ->getMockForAbstractClass();

        $args = [ 'uid=jsmith,ou=people,dc=example,dc=org', 'userpassword', 'secret' ];
        $ldap->expects($this->once())
             ->method('createCompareQuery')
             ->with(...$args)
             ->willReturn($query);

        $this->assertTrue($ldap->compare(...$args));
    }
}

// vim: syntax=php sw=4 ts=4 et:
