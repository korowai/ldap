<?php
/**
 * @file Tests/Adapter/Mock/ResultReferralIteratorTest.php
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
        $interfaces = class_implements(ResultReferralIterator::class);
        $this->assertContains(ResultReferralIteratorInterface::class, $interfaces);
    }

    public function test__extends__BaseIterator()
    {
        $parents = class_parents(ResultReferralIterator::class);
        $this->assertContains(BaseIterator::class, $parents);
    }

    public function test__construct()
    {
        $reference = $this->createMock(ResultReference::class);
        $iterator = new ResultReferralIterator($reference);
        $this->assertSame($reference, $iterator->getReference());
    }
}

// vim: syntax=php sw=4 ts=4 et:
