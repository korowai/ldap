<?php
/**
 * @file Tests/Adapter/Mock/ResultEntryTest.php
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

use Korowai\Lib\Ldap\Adapter\Mock\ResultEntry;
use Korowai\Lib\Ldap\Adapter\Mock\ResultAttributeIterator;
use Korowai\Lib\Ldap\Adapter\ResultEntryInterface;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultEntryTest extends TestCase
{
    public function test__implements__ResultEntryInterface()
    {
        $interfaces = class_implements(ResultEntry::class);
        $this->assertContains(ResultEntryInterface::class, $interfaces);
    }

    public function test__getDn()
    {
        $entry = new ResultEntry('dc=korowai,dc=org', []);
        $this->assertSame('dc=korowai,dc=org', $entry->getDn());
    }

    public function test__getAttributes()
    {
        $entry = new ResultEntry('', ['firstAttr' => ['F'], 'secondAttr' => ['S']]);
        $this->assertSame(['firstattr' => ['F'], 'secondattr' => ['S']], $entry->getAttributes());
    }

    public function test__getAttributeIterator()
    {
        $entry = new ResultEntry('', ['firstAttr' => ['F'], 'secondAttr' => ['S']]);

        $iterator = $entry->getAttributeIterator();
        $this->assertInstanceOf(ResultAttributeIterator::class, $iterator);

        $this->assertSame($entry, $iterator->getEntry());
        $this->assertSame('firstattr', $iterator->key());
        $this->assertSame(['F'], $iterator->current());

        $iterator->next();

        // single iterator instance per ResultEntry
        $iterator2 = $entry->getAttributeIterator();
        $this->assertSame($iterator, $iterator2);
        $this->assertSame('secondattr', $iterator->key());
        $this->assertSame(['S'], $iterator->current());
    }

    public function test__attributes__iteration()
    {
        $entry = new ResultEntry('', ['firstAttr' => ['F'], 'secondAttr' => ['S']]);

        $this->assertSame(['F'], $entry->attributes_reset());
        $this->assertSame('firstAttr', $entry->attributes_key());

        $this->assertSame(['S'], $entry->attributes_next());
        $this->assertSame('secondAttr', $entry->attributes_key());

        $this->assertFalse($entry->attributes_next());
        $this->assertNull($entry->attributes_key());

        $this->assertSame(['F'], $entry->attributes_reset());
        $this->assertSame('firstAttr', $entry->attributes_key());

    }

    public function test__createWithArray__nonFlat()
    {
        $attributes = ['foo' => [ 'FOO' ], 'bar' => [ 'BAR' ]];
        $config = ['dn' => 'dc=example,dc=org', 'attributes' => $attributes];

        $entry = ResultEntry::createWithArray($config);

        $this->assertSame('dc=example,dc=org', $entry->getDn());
        $this->assertSame($attributes, $entry->getAttributes());
    }

    public function test__createWithArray__flat()
    {
        $attributes = ['foo' => [ 'FOO' ], 'bar' => [ 'BAR' ]];
        $config = array_merge(['dn' => 'dc=example,dc=org'], $attributes);

        $entry = ResultEntry::createWithArray($config);

        $this->assertSame('dc=example,dc=org', $entry->getDn());
        $this->assertSame($attributes, $entry->getAttributes());
    }

    public function test__createWithArray__withoutAttributes()
    {
        $config = ['dn' => 'dc=example,dc=org'];

        $entry = ResultEntry::createWithArray($config);

        $this->assertSame('dc=example,dc=org', $entry->getDn());
        $this->assertSame([], $entry->getAttributes());
    }

    public function test__createWithArray__nonArrayAttribute()
    {
        $config = [
            'dn' => 'dc=example,dc=org',
            'attributes' => ['a' => 'A', 'b' => ['B']]
        ];

        $entry = ResultEntry::createWithArray($config);

        $this->assertSame('dc=example,dc=org', $entry->getDn());
        $this->assertSame(['a' => ['A'], 'b' => ['B']], $entry->getAttributes());
    }

    public function test__make__withResultEntry()
    {
        $entry = new ResultEntry('dc=example,dc=org', []);
        $this->assertSame($entry, ResultEntry::make($entry));
    }

    public function test__make__withResultEntryInterface()
    {
        $mock = $this->createMock(ResultEntryInterface::class);
        $mock->expects($this->once())
             ->method('getDn')
             ->with()
             ->willReturn('dc=example,dc=org');
        $mock->expects($this->once())
             ->method('getAttributes')
             ->with()
             ->willReturn(['a' => ['A']]);

        $entry = ResultEntry::make($mock);

        $this->assertInstanceOf(ResultEntry::class, $entry);
        $this->assertSame('dc=example,dc=org', $entry->getDn());
        $this->assertSame(['a' => ['A']], $entry->getAttributes());
    }

    public function test__make__withArray()
    {
        $config = ['dn' => 'dc=example,dc=org', 'a' => 'A'];

        $entry = ResultEntry::make($config);

        $this->assertInstanceOf(ResultEntry::class, $entry);
        $this->assertSame('dc=example,dc=org', $entry->getDn());
        $this->assertSame(['a' => ['A']], $entry->getAttributes());
    }

    public function test__make__withInvalidArgument()
    {
        $msg = 'parameter 1 to Korowai\\Lib\\Ldap\\Adapter\\Mock\\ResultEntry::make() must be ' .
               'an instance of Korowai\\Lib\\Ldap\\Adapter\\ResultEntryInterface or an array, not string';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($msg);

        ResultEntry::make('foo');
    }
}

// vim: syntax=php sw=4 ts=4 et:
