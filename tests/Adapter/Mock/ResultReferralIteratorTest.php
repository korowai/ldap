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

use Korowai\Lib\Ldap\Adapter\Mock\ResultReferralIterator;
use Korowai\Lib\Ldap\Adapter\Mock\ResultReference;
use Korowai\Lib\Ldap\Adapter\ResultReferralIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferralIterator as BaseIterator;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferralIteratorTest extends TestCase
{
    public function test__implements__ResultReferralIteratorInterface()
    {
        $this->assertImplementsInterface(ResultReferralIteratorInterface::class, ResultReferralIterator::class);
    }

    public function test__extends__BaseIterator()
    {
        $this->assertExtendsClass(BaseIterator::class, ResultReferralIterator::class);
    }

    public function test__construct()
    {
        $reference = $this->createMock(ResultReference::class);
        $iterator = new ResultReferralIterator($reference);
        $this->assertSame($reference, $iterator->getReference());
    }
}

// vim: syntax=php sw=4 ts=4 et:
