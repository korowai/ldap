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

use Korowai\Lib\Ldap\Adapter\Mock\ResultAttributeIterator;
use Korowai\Lib\Ldap\Adapter\Mock\ResultEntry;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultAttributeIteratorTest extends TestCase
{
    public function test__implements__Iterator()
    {
        $this->assertImplementsInterface(\Iterator::class, ResultAttributeIterator::class);
    }

    public function test__getEntry()
    {
        $entry = $this->createMock(ResultEntry::class);
        $iterator = new ResultAttributeIterator($entry);
        $this->assertSame($entry, $iterator->getEntry());
    }

    public function test__current()
    {
        $values = ['F'];
        $entry = $this->createMock(ResultEntry::class);
        $iterator = new ResultAttributeIterator($entry);
        $entry->expects($this->once())
              ->method('attributes_current')
              ->with()
              ->willReturn(['X']);
        $this->assertSame(['X'], $iterator->current());
    }

    public function test__key()
    {
        $entry = $this->createMock(ResultEntry::class);
        $iterator = new ResultAttributeIterator($entry);
        $entry->expects($this->once())
              ->method('attributes_key')
              ->with()
              ->willReturn('attribName');
        $this->assertSame('attribname', $iterator->key());
    }

    public function test__next()
    {
        $entry = $this->createMock(ResultEntry::class);
        $iterator = new ResultAttributeIterator($entry);

        $entry->expects($this->once())
              ->method('attributes_next')
              ->with();
        $iterator->next();
    }

    public function test__rewind()
    {
        $entry = $this->createMock(ResultEntry::class);
        $iterator = new ResultAttributeIterator($entry);

        $entry->expects($this->once())
              ->method('attributes_reset')
              ->with();
        $iterator->rewind();
    }

    public function test__valid()
    {
        $entry = $this->createMock(ResultEntry::class);
        $iterator = new ResultAttributeIterator($entry);

        $entry->expects($this->exactly(2))
              ->method('attributes_key')
              ->withConsecutive([], [])
              ->will($this->onConsecutiveCalls(['firstAttr', null]));

        $this->assertTrue($iterator->valid());
        $this->assertFalse($iterator->valid());
    }
}

// vim: syntax=php sw=4 ts=4 et:
