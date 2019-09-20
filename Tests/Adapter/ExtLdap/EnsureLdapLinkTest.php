<?php
/**
 * @file packages/ldaplib/Tests/Adapter/ExtLdap/EnsureLdapLinkTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Tests\Adapter\ExtLdap;

use PHPUnit\Framework\TestCase;
use Korowai\Lib\Ldap\Adapter\ExtLdap\EnsureLdapLink;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class EnsureLdapLinkTest extends TestCase
{
    use EnsureLdapLink;

    public function test_ensureLdapLink_Failure()
    {
      $link = $this->createMock(LdapLink::class);
      $link->expects($this->once())
           ->method('isValid')
           ->with()
           ->willReturn(false);

      $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
      $this->expectExceptionMessage('Uninitialized LDAP link');
      $this->expectExceptionCode(-1);
      static::ensureLdapLink($link);
    }

    public function test_ensureLdapLink_Success()
    {
      $link = $this->createMock(LdapLink::class);
      $link->expects($this->once())
           ->method('isValid')
           ->with()
           ->willReturn(true);
      $this->assertTrue(static::ensureLdapLink($link));
    }
}

// vim: syntax=php sw=4 ts=4 et:
