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

use Korowai\Lib\Ldap\Adapter\Mock\ResultEntryIterator;
use Korowai\Lib\Ldap\Adapter\Mock\ResultEntry;
use Korowai\Lib\Ldap\Adapter\Mock\Result;
use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultEntryIteratorTest extends TestCase
{
    public function test__implements__ResultEntryIteratorInterface()
    {
        $this->assertImplementsInterface(ResultEntryIteratorInterface::class, ResultEntryIterator::class);
    }

    public function test__getResult()
    {
        $result = $this->createMock(Result::class);
        $iterator = new ResultEntryIterator($result);
        $this->assertSame($result, $iterator->getResult());
    }

    public function test__getEntry()
    {
        $entry = $this->createMock(ResultEntry::class);
        $result = new Result([$entry]);
        $iterator = new ResultEntryIterator($result);

        $this->assertSame($entry, $iterator->getEntry());
    }

    public function test__iteration()
    {
        $entry1 = $this->createMock(ResultEntry::class);
        $entry2 = $this->createMock(ResultEntry::class);

        $entry1->expects($this->any())
               ->method('getDn')
               ->with()
               ->willReturn('dc=dc1,dc=foo');

        $entry2->expects($this->any())
               ->method('getDn')
               ->with()
               ->willReturn('dc=dc2,dc=foo');

        $result = new Result([$entry1, $entry2]);
        $iterator = new ResultEntryIterator($result);

        $this->assertSame('dc=dc1,dc=foo', $iterator->key());
        $this->assertSame($entry1, $iterator->current());
        $this->assertTrue($iterator->valid());

        $iterator->next();

        $this->assertSame('dc=dc2,dc=foo', $iterator->key());
        $this->assertSame($entry2, $iterator->current());
        $this->assertTrue($iterator->valid());

        $iterator->next();

        $this->assertNull($iterator->key());
        $this->assertFalse($iterator->current());
        $this->assertFalse($iterator->valid());

        $iterator->rewind();

        $this->assertSame('dc=dc1,dc=foo', $iterator->key());
        $this->assertSame($entry1, $iterator->current());
        $this->assertTrue($iterator->valid());
    }
}

// vim: syntax=php sw=4 ts=4 et:
