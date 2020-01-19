<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Exception;

use Korowai\Testing\TestCase;
use Korowai\Lib\Ldap\Exception\AttributeException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AttributeExceptionTest extends TestCase
{
    public function test__extendsOutOfRangeException()
    {
        $this->assertExtendsClass(\OutOfRangeException::class, AttributeException::class);
    }

    public static function getMessage__cases()
    {
        return [
            'default message' => [[], 'No such attribute'],
            'custom message'  => [['custom message'], 'custom message']
        ];
    }

    /**
     * @dataProvider getMessage__cases
     */
    public function test__getMessage(array $args, string $expect)
    {
        $e = new AttributeException(...$args);
        $this->assertEquals($expect, $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et:
