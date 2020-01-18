<?php
/**
 * @file Tests/Adapter/Mock/ResultReferenceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Tests\Adapter\Mock;

use PHPUnit\Framework\TestCase;

use Korowai\Lib\Ldap\Adapter\Mock\ResultReference;
use Korowai\Lib\Ldap\Adapter\Mock\ResultReferralIterator;
use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ReferralsIterationInterface;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferenceTest extends TestCase
{
    public function test__implements__ResultReferenceInterface()
    {
        $interfaces = class_implements(ResultReference::class);
        $this->assertContains(ResultReferenceInterface::class, $interfaces);
    }

    public function test__implements__ResultReferralArrayIterationInterface()
    {
        $interfaces = class_implements(ResultReference::class);
        $this->assertContains(ReferralsIterationInterface::class, $interfaces);
    }

    public function test__getDn()
    {
        $reference = new ResultReference('dc=korowai,dc=org', []);
        $this->assertSame('dc=korowai,dc=org', $reference->getDn());
    }

    public function test__getReferrals()
    {
        $reference = new ResultReference('', ['A', 'B']);
        $this->assertSame(['A', 'B'], $reference->getReferrals());
    }

    public function test__getReferralIterator()
    {
        $reference = new ResultReference('', ['A', 'B']);

        $iterator = $reference->getReferralIterator();
        $this->assertInstanceOf(ResultReferralIterator::class, $iterator);

        $this->assertSame($reference, $iterator->getReference());
        $this->assertSame(0, $iterator->key());
        $this->assertSame('A', $iterator->current());

        $iterator->next();

        // single iterator instance per ResultReference
        $iterator2 = $reference->getReferralIterator();
        $this->assertSame($iterator, $iterator2);
        $this->assertSame(1, $iterator->key());
        $this->assertSame('B', $iterator->current());
    }

    public function test__referrals__iteration()
    {
        $reference = new ResultReference('', ['A', 'B']);

        $this->assertSame(0, $reference->referrals_key());
        $this->assertSame('A', $reference->referrals_current());

        $this->assertSame('B', $reference->referrals_next());

        $this->assertSame(1, $reference->referrals_key());
        $this->assertSame('B', $reference->referrals_current());

        $this->assertFalse($reference->referrals_next());

        $this->assertNull($reference->referrals_key());
        $this->assertFalse($reference->referrals_current());

        $this->assertSame('A', $reference->referrals_reset());
        $this->assertSame(0, $reference->referrals_key());
        $this->assertSame('A', $reference->referrals_current());
    }

    public function test__createWithArray__nonFlat()
    {
        $referrals = [ 'foo', 'bar' ];
        $config = ['dn' => 'dc=example,dc=org', 'referrals' => $referrals];

        $reference = ResultReference::createWithArray($config);

        $this->assertSame('dc=example,dc=org', $reference->getDn());
        $this->assertSame($referrals, $reference->getReferrals());
    }

    public function test__createWithArray__flat()
    {
        $referrals = [ 'foo', 'bar' ];
        $config = array_merge(['dn' => 'dc=example,dc=org'], $referrals);

        $reference = ResultReference::createWithArray($config);

        $this->assertSame('dc=example,dc=org', $reference->getDn());
        $this->assertSame($referrals, $reference->getReferrals());
    }

    public function test__createWithArray__withoutReferrals()
    {
        $config = ['dn' => 'dc=example,dc=org'];

        $reference = ResultReference::createWithArray($config);

        $this->assertSame('dc=example,dc=org', $reference->getDn());
        $this->assertSame([], $reference->getReferrals());
    }

    public function test__make__withResultReference()
    {
        $reference = new ResultReference('dc=example,dc=org', []);
        $this->assertSame($reference, ResultReference::make($reference));
    }

    public function test__make__withResultReferenceInterface()
    {
        $mock = $this->createMock(ResultReferenceInterface::class);
        $mock->expects($this->once())
             ->method('getDn')
             ->with()
             ->willReturn('dc=example,dc=org');
        $mock->expects($this->once())
             ->method('getReferrals')
             ->with()
             ->willReturn(['A']);

        $reference = ResultReference::make($mock);

        $this->assertInstanceOf(ResultReference::class, $reference);
        $this->assertSame('dc=example,dc=org', $reference->getDn());
        $this->assertSame(['A'], $reference->getReferrals());
    }

    public function test__make__withArray()
    {
        $config = ['dn' => 'dc=example,dc=org', 'A'];

        $reference = ResultReference::make($config);

        $this->assertInstanceOf(ResultReference::class, $reference);
        $this->assertSame('dc=example,dc=org', $reference->getDn());
        $this->assertSame(['A'], $reference->getReferrals());
    }

    public function test__make__withInvalidArgument()
    {
        $msg = 'parameter 1 to Korowai\\Lib\\Ldap\\Adapter\\Mock\\ResultReference::make() must be ' .
               'an instance of Korowai\\Lib\\Ldap\\Adapter\\ResultReferenceInterface or an array, not string';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($msg);

        ResultReference::make('foo');
    }
}

// vim: syntax=php sw=4 ts=4 et:
