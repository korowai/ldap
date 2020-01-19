<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\Mock;

use Korowai\Testing\TestCase;

use Korowai\Lib\Ldap\Adapter\Mock\ResultReferenceIterator;
use Korowai\Lib\Ldap\Adapter\Mock\ResultReference;
use Korowai\Lib\Ldap\Adapter\Mock\Result;
use Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferenceIteratorTest extends TestCase
{
    public function test__implements__ResultReferenceIteratorInetrface()
    {
        $this->assertImplementsInterface(ResultReferenceIteratorInterface::class, ResultReferenceIterator::class);
    }

    public function test__getResult()
    {
        $result = $this->createMock(Result::class);
        $iterator = new ResultReferenceIterator($result);
        $this->assertSame($result, $iterator->getResult());
    }

    public function test__key()
    {
        $result = $this->createMock(Result::class);
        $iterator = new ResultReferenceIterator($result);
        $reference = $this->createMock(ResultReference::class);

        $result->expects($this->once())
               ->method('references_current')
               ->with()
               ->willReturn($reference);

        $reference->expects($this->once())
                  ->method('getDn')
                  ->with()
                  ->willReturn('cn=ref1,dc=foo');

        $this->assertSame('cn=ref1,dc=foo', $iterator->key());
    }

    public function test__current()
    {
        $result = $this->createMock(Result::class);
        $iterator = new ResultReferenceIterator($result);
        $reference = $this->createMock(ResultReference::class);

        $result->expects($this->once())
               ->method('references_current')
               ->with()
               ->willReturn($reference);

        $this->assertSame($reference, $iterator->current());
    }

    public function test__next()
    {
        $result = $this->createMock(Result::class);
        $iterator = new ResultReferenceIterator($result);
        $reference = $this->createMock(ResultReference::class);

        $result->expects($this->once())
               ->method('references_next')
               ->with()
               ->willReturn($reference);

        $this->assertNull($iterator->next());
    }

    public function test__rewind()
    {
        $result = $this->createMock(Result::class);
        $iterator = new ResultReferenceIterator($result);
        $reference = $this->createMock(ResultReference::class);

        $result->expects($this->once())
               ->method('references_reset')
               ->with()
               ->willReturn($reference);

        $this->assertNull($iterator->rewind());
    }

    public function test__valid()
    {
        $result = $this->createMock(Result::class);
        $iterator = new ResultReferenceIterator($result);
        $reference = $this->createMock(ResultReference::class);

        $result->expects($this->exactly(2))
               ->method('references_current')
               ->withConsecutive([], [])
               ->will($this->onConsecutiveCalls($reference, false));

        $reference->expects($this->once())
                  ->method('getDn')
                  ->with()
                  ->willReturn('cn=ref1,dc=foo');

        $this->assertTrue($iterator->valid());
        $this->assertFalse($iterator->valid());
    }
}

// vim: syntax=php sw=4 ts=4 et:
