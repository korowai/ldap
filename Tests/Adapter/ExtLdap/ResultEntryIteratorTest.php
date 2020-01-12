<?php
/**
 * @file Tests/Adapter/ExtLdap/ResultEntryIteratorTest.php
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

use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntryIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntry;
use Korowai\Lib\Ldap\Adapter\ExtLdap\Result;
use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultEntryIteratorTest extends TestCase
{
    public function test__implements__ResultEntryIteratorInterface()
    {
        $interfaces = class_implements(ResultEntryIterator::class);
        $this->assertContains(ResultEntryIteratorInterface::class, $interfaces);
    }

    public function test__getResult()
    {
        $result = $this->createMock(Result::class);
        $entry = $this->createMock(ResultEntry::class);
        $iterator = new ResultEntryIterator($result, $entry);
        $this->assertSame($result, $iterator->getResult());
    }

    public function test__getEntry()
    {
        $result = $this->createMock(Result::class);
        $entry = $this->createMock(ResultEntry::class);
        $iterator = new ResultEntryIterator($result, $entry);
        $this->assertSame($entry, $iterator->getEntry());
    }

    public function test__current()
    {
        $result = $this->createMock(Result::class);
        $entry = $this->createMock(ResultEntry::class);
        $iterator = new ResultEntryIterator($result, $entry);
        $this->assertSame($entry, $iterator->current());
    }

    public function test__key()
    {
        $result = $this->createMock(Result::class);
        $entry = $this->createMock(ResultEntry::class);
        $iterator = new ResultEntryIterator($result, $entry);

        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');

        $this->assertEquals('dc=korowai,dc=org', $iterator->key());
    }

    public function test__next()
    {
        $result = $this->createMock(Result::class);
        $entry1 = $this->createMock(ResultEntry::class);
        $entry2 = $this->createMock(ResultEntry::class);
        $iterator = new ResultEntryIterator($result, $entry1);

        $this->assertSame($entry1, $iterator->getEntry());

        $entry1->expects($this->once())
               ->method('next_entry')
               ->with()
               ->willReturn($entry2);
        $entry2->method('next_entry')
               ->willReturn(null);

        $iterator->next();
        $this->assertSame($entry2, $iterator->getEntry());
        $iterator->next();
        $this->assertNull($iterator->getEntry());
    }

    public function test__rewind()
    {
        $result = $this->createMock(Result::class);
        $entry1 = $this->createMock(ResultEntry::class);
        $entry2 = $this->createMock(ResultEntry::class);
        $iterator = new ResultEntryIterator($result, $entry2);

        $this->assertSame($entry2, $iterator->getEntry());

        $result->expects($this->once())
               ->method('first_entry')
               ->with()
               ->willReturn($entry1);

        $this->assertSame($entry2, $iterator->getEntry());
        $iterator->rewind();
        $this->assertSame($entry1, $iterator->getEntry());
    }

    public function test__valid()
    {
        $result = $this->createMock(Result::class);
        $entry1 = $this->createMock(ResultEntry::class);
        $entry2 = $this->createMock(ResultEntry::class);
        $iterator = new ResultEntryIterator($result, $entry1);

        $this->assertSame($entry1, $iterator->getEntry());

        $entry1->expects($this->once())
               ->method('next_entry')
               ->with()
               ->willReturn($entry2);
        $entry2->method('next_entry')
               ->willReturn(null);

        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertFalse($iterator->valid());
    }
}

// vim: syntax=php sw=4 ts=4 et:
