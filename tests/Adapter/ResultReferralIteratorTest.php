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

use Korowai\Lib\Ldap\Adapter\ResultReferralIterator;
use Korowai\Lib\Ldap\Adapter\ReferralsIterationInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferralIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferralIteratorTest extends TestCase
{
    public function test__implements__ResultReferralIteratorInterface()
    {
        $this->assertImplementsInterface(ResultReferralIteratorInterface::class, ResultReferralIterator::class);
    }

    public function test__getReference()
    {
        $reference = $this->createMock(ReferralsIterationInterface::class);
        $iterator = new ResultReferralIterator($reference);
        $this->assertSame($reference, $iterator->getReference());
    }

    public function test__iteration()
    {
        $referrals = ['A', 'B'];
        $reference = $this->createMock(ReferralsIterationInterface::class);
        $iterator = new ResultReferralIterator($reference);

        $reference->expects($this->exactly(2))
              ->method('referrals_next')
              ->with()
              ->will($this->returnCallback(function () use (&$referrals) {
                  next($referrals);
              }));
        $reference->expects($this->exactly(8))
              ->method('referrals_key')
              ->with()
              ->will($this->returnCallback(function () use (&$referrals) {
                  return key($referrals);
              }));
        $reference->expects($this->exactly(4))
              ->method('referrals_current')
              ->with()
              ->will($this->returnCallback(function () use (&$referrals) {
                  return current($referrals);
              }));
        $reference->expects($this->exactly(1))
              ->method('referrals_reset')
              ->with()
              ->will($this->returnCallback(function () use (&$referrals) {
                  return reset($referrals);
              }));

        $this->assertSame(0, $iterator->key());
        $this->assertSame('A', $iterator->current());
        $this->assertTrue($iterator->valid());

        $iterator->next();

        $this->assertSame(1, $iterator->key());
        $this->assertSame('B', $iterator->current());
        $this->assertTrue($iterator->valid());

        $iterator->next();

        $this->assertNull($iterator->key());
        $this->assertFalse($iterator->current());
        $this->assertFalse($iterator->valid());

        $iterator->rewind();

        $this->assertSame(0, $iterator->key());
        $this->assertSame('A', $iterator->current());
        $this->assertTrue($iterator->valid());
    }
}

// vim: syntax=php sw=4 ts=4 et:
