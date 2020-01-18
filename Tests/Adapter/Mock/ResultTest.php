<?php
/**
 * @file Tests/Adapter/Mock/ResultTest.php
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

use Korowai\Lib\Ldap\Adapter\Mock\Result;
use Korowai\Lib\Ldap\Adapter\ResultInterface;
use Korowai\Lib\Ldap\Adapter\Mock\ResultEntry;
use Korowai\Lib\Ldap\Adapter\Mock\ResultReference;
use Korowai\Lib\Ldap\Adapter\Mock\ResultEntryIterator;
use Korowai\Lib\Ldap\Adapter\Mock\ResultReferenceIterator;
use Korowai\Lib\Ldap\EntryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultTest extends TestCase
{
    public function test__implements__ResultInterface()
    {
        $interfaces = class_implements(Result::class);
        $this->assertContains(ResultInterface::class, $interfaces);
    }

    public function test__entries__iteration()
    {
        $one = $this->createMock(ResultEntry::class);
        $two = $this->createMock(ResultEntry::class);

        $result = new Result([$one, $two]);

        $one->expects($this->any())
            ->method('getDn')
            ->with()
            ->willReturn('dc=one');

        $two->expects($this->any())
            ->method('getDn')
            ->with()
            ->willReturn('dc=two');

        $this->assertSame($one, $result->entries_current());
        $this->assertSame('dc=one', $result->entries_key());

        $this->assertSame($two, $result->entries_next());
        $this->assertSame('dc=two', $result->entries_key());

        $this->assertFalse($result->entries_next());
        $this->assertNull($result->entries_key());

        $this->assertSame($one, $result->entries_reset());
        $this->assertSame('dc=one', $result->entries_key());
    }

    public function test__references__iteration()
    {
        $one = $this->createMock(ResultReference::class);
        $two = $this->createMock(ResultReference::class);

        $result = new Result([], [$one, $two]);

        $one->expects($this->any())
            ->method('getDn')
            ->with()
            ->willReturn('dc=one');

        $two->expects($this->any())
            ->method('getDn')
            ->with()
            ->willReturn('dc=two');

        $this->assertSame($one, $result->references_current());
        $this->assertSame('dc=one', $result->references_key());

        $this->assertSame($two, $result->references_next());
        $this->assertSame('dc=two', $result->references_key());

        $this->assertFalse($result->references_next());
        $this->assertNull($result->references_key());

        $this->assertSame($one, $result->references_reset());
        $this->assertSame('dc=one', $result->references_key());
    }

    public function test__getResultEntries()
    {
        $result = new Result(['E1', 'E2']);
        $this->assertSame(['E1', 'E2'], $result->getResultEntries());
    }

    public function test__getResultReferences()
    {
        $result = new Result([], ['R1', 'R2']);
        $this->assertSame(['R1', 'R2'], $result->getResultReferences());
    }

    public function test__getResultEntryIterator()
    {
        $result = new Result([]);
        $iterator = $result->getResultEntryIterator();

        $this->assertInstanceOf(ResultEntryIterator::class, $iterator);
        $this->assertSame($result, $iterator->getResult());
    }

    public function test__getResultReferenceIterator()
    {
        $result = new Result([]);
        $iterator = $result->getResultReferenceIterator();

        $this->assertInstanceOf(ResultReferenceIterator::class, $iterator);
        $this->assertSame($result, $iterator->getResult());
    }

    public function test__foreachEntry()
    {
        $entries = [
            $this->createMock(ResultEntry::class),
            $this->createMock(ResultEntry::class),
            $this->createMock(ResultEntry::class)
        ];

        $result = new Result($entries);

        // Entries 0 and 2 have same dn
        $entries[0]->expects($this->any())
                   ->method('getDn')
                   ->with()
                   ->willReturn('dc=a');

        $entries[1]->expects($this->any())
                   ->method('getDn')
                   ->with()
                   ->willReturn('dc=b');

        $entries[2]->expects($this->any())
                   ->method('getDn')
                   ->with()
                   ->willReturn('dc=a');

        $count = 0;
        foreach($result as $dn => $entry) {
            $this->assertSame($entries[$count]->getDn(), $dn);
            $this->assertInstanceOf(EntryInterface::class, $entry);
            $count++;
        }
        $this->assertSame(3, $count);
    }

    public function test__foreachResultEntry()
    {
        $entries = [
            $this->createMock(ResultEntry::class),
            $this->createMock(ResultEntry::class),
            $this->createMock(ResultEntry::class)
        ];

        $result = new Result($entries);

        // Entries 0 and 2 have same dn
        $entries[0]->expects($this->any())
                   ->method('getDn')
                   ->with()
                   ->willReturn('dc=a');

        $entries[1]->expects($this->any())
                   ->method('getDn')
                   ->with()
                   ->willReturn('dc=b');

        $entries[2]->expects($this->any())
                   ->method('getDn')
                   ->with()
                   ->willReturn('dc=a');

        $count = 0;
        foreach($result->getResultEntryIterator() as $dn => $entry) {
            $this->assertSame($entries[$count]->getDn(), $dn);
            $this->assertSame($entries[$count], $entry);
            $count++;
        }
        $this->assertSame(3, $count);
    }

    public function test__foreachResultReference()
    {
        $references = [
            $this->createMock(ResultReference::class),
            $this->createMock(ResultReference::class),
            $this->createMock(ResultReference::class)
        ];

        $result = new Result([], $references);

        // References 0 and 2 have same dn
        $references[0]->expects($this->any())
                   ->method('getDn')
                   ->with()
                   ->willReturn('dc=a');

        $references[1]->expects($this->any())
                   ->method('getDn')
                   ->with()
                   ->willReturn('dc=b');

        $references[2]->expects($this->any())
                   ->method('getDn')
                   ->with()
                   ->willReturn('dc=a');

        $count = 0;
        foreach($result->getResultReferenceIterator() as $dn => $reference) {
            $this->assertSame($references[$count]->getDn(), $dn);
            $this->assertSame($references[$count], $reference);
            $count++;
        }
        $this->assertSame(3, $count);
    }

    public function test__createWithArray__nonFlatWithObjects()
    {
        $entries = [
            new ResultEntry('dc=a', ['foo' => 'FOO-a']),
            new ResultEntry('dc=b', ['bar' => 'BAR-b'])
        ];
        $references = [
            new ResultReference('dc=c', ['geez'])
        ];

        $config = ['entries' => $entries, 'references' => $references];

        $result = Result::createWithArray($config);

        $this->assertSame($entries, $result->getResultEntries());
        $this->assertSame($references, $result->getResultReferences());
    }

    public function test__createWithArray__flatWithObjects()
    {
        $entries = [
            new ResultEntry('dc=a', ['foo' => 'FOO-a']),
            new ResultEntry('dc=b', ['bar' => 'BAR-b'])
        ];
        $references = [
            new ResultReference('dc=c', ['geez'])
        ];

        $config = array_merge($entries, ['references' => $references]);

        $result = Result::createWithArray($config);

        $this->assertSame($entries, $result->getResultEntries());
        $this->assertSame($references, $result->getResultReferences());
    }

    public function test__createWithArray__withoutReferences()
    {
        $entries = [
            new ResultEntry('dc=a', ['foo' => 'FOO-a']),
            new ResultEntry('dc=b', ['bar' => 'BAR-b'])
        ];

        $result = Result::createWithArray($entries);

        $this->assertSame($entries, $result->getResultEntries());
    }

    public function test__make__withResult()
    {
        $result = $this->createMock(Result::class);
        $this->assertSame($result, Result::make($result));
    }

    public function test__make__withResultInterface()
    {
        $entries = ['e1', 'e2'];
        $references = ['r1', 'r2'];

        $result1 = $this->getMockBuilder(ResultInterface::class)
                        ->getMockForAbstractClass();

        $result1->expects($this->once())
                  ->method('getResultEntries')
                  ->with()
                  ->willReturn($entries);

        $result1->expects($this->once())
                  ->method('getResultReferences')
                  ->with()
                  ->willReturn($references);


        $result2 = Result::make($result1);

        $this->assertNotSame($result2, $result1);
        $this->assertSame($entries, $result2->getResultEntries());
        $this->assertSame($references, $result2->getResultReferences());
    }

    public function test__make__withArray()
    {
        $entries = [
            new ResultEntry('dc=a', ['foo' => 'FOO-a']),
            new ResultEntry('dc=b', ['bar' => 'BAR-b'])
        ];
        $references = [
            new ResultReference('dc=c', ['geez'])
        ];

        $config = array_merge($entries, ['references' => $references]);

        $result = Result::make($config);

        $this->assertSame($entries, $result->getResultEntries());
        $this->assertSame($references, $result->getResultReferences());
    }

    public function test__make__withWrongArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $msg = 'parameter 1 to Result::make() must be an instance of ' .
               'ResultInterface or an array, not string';
        $this->expectExceptionMessage($msg);

        Result::make('foo');
    }
}

// vim: syntax=php sw=4 ts=4 et:
