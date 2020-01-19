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

use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReference;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReferralIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\Result;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLink;
use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ReferralsIterationInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferenceTest extends TestCase
{
    private function getResultMock($link = null)
    {
        $result = $this->createMock(Result::class);
        $result->expects($this->any())
               ->method('getLink')
               ->with()
               ->willReturn($link);
        return $result;
    }

    public function test__implements__ResultReferenceInterface()
    {
        $this->assertImplementsInterface(ResultReferenceInterface::class, ResultReference::class);
    }

    public function test__implements__ResultReferralArrayIterationInterface()
    {
        $this->assertImplementsInterface(ReferralsIterationInterface::class, ResultReference::class);
    }

    public function test__getResource()
    {
        $result = $this->getResultMock();
        $ref = new ResultReference('ldap reference', $result);
        $this->assertSame('ldap reference', $ref->getResource());
    }

    public function test__getResult()
    {
        $result = $this->getResultMock();
        $ref = new ResultReference('ldap reference', $result);
        $this->assertSame($result, $ref->getResult());
    }

    public function test__getReferralIterator()
    {
        $result = $this->getResultMock();
        $ref = new ResultReference('ldap reference', $result);

        $iterator1 = $ref->getReferralIterator();
        $this->assertInstanceOf(ResultReferralIterator::class, $iterator1);

        $iterator2 = $ref->getReferralIterator();
        $this->assertSame($iterator1, $iterator2);
    }

    public function test__next_reference()
    {
        $link = $this->createMock(LdapLink::class);
        $result = $this->getResultMock($link);

        $ref = new ResultReference('ldap reference', $result);

        $link->expects($this->once())
             ->method('next_reference')
             ->with($this->identicalTo($ref))
             ->willReturn('next reference');

        $this->assertSame('next reference', $ref->next_reference());
    }

    public function test__parse_reference()
    {
        $link = $this->createMock(LdapLink::class);
        $result = $this->getResultMock($link);

        $ref = new ResultReference('ldap reference', $result);

        $callback = function ($ref, &$referrals) {
            $referrals = ['A'];
            return 'ok';
        };

        $link->expects($this->once())
             ->method('parse_reference')
             ->with($this->identicalTo($ref), $this->anything())
             ->will($this->returnCallback($callback));

        $this->assertSame('ok', $ref->parse_reference($referrals));
        $this->assertSame(['A'], $referrals);
    }

    public function test__getReferrals__Failure()
    {
        $link = $this->createMock(LdapLink::class);
        $result = $this->getResultMock($link);

        $ref = new ResultReference('ldap reference', $result);

        $link->expects($this->once())
             ->method('parse_reference')
             ->with($this->identicalTo($ref), $this->anything())
             ->willReturn(false);
        $link->expects($this->once())
             ->method('errno')
             ->willReturn(0x54);

        $this->expectException(LdapException::class);
        $this->expectExceptionCode(0x54);

        $ref->getReferrals();
    }

    public function test__getReferrals__Success()
    {
        $link = $this->createMock(LdapLink::class);
        $result = $this->getResultMock($link);

        $ref = new ResultReference('ldap reference', $result);

        $callback = function ($ref, &$referrals) {
            $referrals = ['A'];
            return true;
        };

        $link->expects($this->once())
             ->method('parse_reference')
             ->with($this->identicalTo($ref), $this->anything())
             ->will($this->returnCallback($callback));

        $this->assertSame(['A'], $ref->getReferrals());
    }

    public function test__referrals__iteration()
    {
        $link = $this->createMock(LdapLink::class);
        $result = $this->getResultMock($link);

        $ref = new ResultReference('ldap reference', $result);

        $callback = function ($ref, &$referrals) {
            $referrals = ['A', 'B'];
            return true;
        };

        $link->expects($this->once())
             ->method('parse_reference')
             ->with($this->identicalTo($ref), $this->anything())
             ->will($this->returnCallback($callback));

        $this->assertSame(0, $ref->referrals_key());
        $this->assertSame('A', $ref->referrals_current());

        $this->assertSame('B', $ref->referrals_next());

        $this->assertSame(1, $ref->referrals_key());
        $this->assertSame('B', $ref->referrals_current());

        $this->assertFalse($ref->referrals_next());
        $this->assertNull($ref->referrals_key());
        $this->assertFalse($ref->referrals_current());

        $ref->referrals_reset();

        $this->assertSame(0, $ref->referrals_key());
        $this->assertSame('A', $ref->referrals_current());
    }
}

// vim: syntax=php sw=4 ts=4 et:
