<?php
/**
 * @file Tests/Adapter/ExtLdap/ResultReferenceIteratorTest.php
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

use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReferenceIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReference;
use Korowai\Lib\Ldap\Adapter\ExtLdap\Result;
use Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferenceIteratorTest extends TestCase
{
    public function test__implements__ResultReferenceIteratorInetrface()
    {
        $interfaces = class_implements(ResultReferenceIterator::class);
        $this->assertContains(ResultReferenceIteratorInterface::class, $interfaces);
    }

    public function test__getResult()
    {
        $result = $this->createMock(Result::class);
        $ref = $this->createMock(ResultReference::class);
        $iterator = new ResultReferenceIterator($result, $ref);
        $this->assertSame($result, $iterator->getResult());
    }

    public function test__getReference()
    {
        $result = $this->createMock(Result::class);
        $ref = $this->createMock(ResultReference::class);
        $iterator = new ResultReferenceIterator($result, $ref);
        $this->assertSame($ref, $iterator->getReference());
    }

    public function test__current()
    {
        $result = $this->createMock(Result::class);
        $ref = $this->createMock(ResultReference::class);
        $iterator = new ResultReferenceIterator($result, $ref);
        $this->assertSame($ref, $iterator->current());
    }

    public function test__key()
    {
        $result = $this->createMock(Result::class);
        $ref = $this->createMock(ResultReference::class);
        $iterator = new ResultReferenceIterator($result, $ref);

        $ref->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');

        $this->assertEquals('dc=korowai,dc=org', $iterator->key());
    }

    public function test__next()
    {
        $result = $this->createMock(Result::class);
        $ref1 = $this->createMock(ResultReference::class);
        $ref2 = $this->createMock(ResultReference::class);
        $iterator = new ResultReferenceIterator($result, $ref1);

        $this->assertSame($ref1, $iterator->getReference());

        $ref1->expects($this->once())
               ->method('next_reference')
               ->with()
               ->willReturn($ref2);
        $ref2->method('next_reference')
               ->willReturn(null);

        $iterator->next();
        $this->assertSame($ref2, $iterator->getReference());
        $iterator->next();
        $this->assertNull($iterator->getReference());
    }

    public function test__rewind()
    {
        $result = $this->createMock(Result::class);
        $ref1 = $this->createMock(ResultReference::class);
        $ref2 = $this->createMock(ResultReference::class);
        $iterator = new ResultReferenceIterator($result, $ref2);

        $this->assertSame($ref2, $iterator->getReference());

        $result->expects($this->once())
               ->method('first_reference')
               ->with()
               ->willReturn($ref1);

        $this->assertSame($ref2, $iterator->getReference());
        $iterator->rewind();
        $this->assertSame($ref1, $iterator->getReference());
    }

    public function test__valid()
    {
        $result = $this->createMock(Result::class);
        $ref1 = $this->createMock(ResultReference::class);
        $ref2 = $this->createMock(ResultReference::class);
        $iterator = new ResultReferenceIterator($result, $ref1);

        $this->assertSame($ref1, $iterator->getReference());

        $ref1->expects($this->once())
               ->method('next_reference')
               ->with()
               ->willReturn($ref2);
        $ref2->method('next_reference')
               ->willReturn(null);

        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertFalse($iterator->valid());
    }
}

// vim: syntax=php sw=4 ts=4 et:
