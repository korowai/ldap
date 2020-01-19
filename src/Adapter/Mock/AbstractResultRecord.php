<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\Mock;

use Korowai\Lib\Ldap\Adapter\ResultRecordInterface;

/**
 * Common functions for ResultEntry and ResultReference.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractResultRecord implements ResultRecordInterface
{
    /** @var Result */
    private $dn;

    abstract public static function getInterfaceName() : string;
    abstract public static function createWithInterface($record);
    abstract public static function createWithArray(array $record);

    /**
     * Returns a ResultEntry/ResultReference object made out of *$record* argument.
     *
     * @param  mixed $record an instance of ResultEntryInterface/ResultReferenceInterface or an array.
     *
     * @return ResultEntry
     * @throws \InvalidArgumentException when *$record* is of wrong type
     */
    public static function make($record)
    {
        if ($record instanceof static) {
            return $record;
        } elseif (is_a($record, static::getInterfaceName())) {
            return static::createWithInterface($record);
        } elseif (is_array($record)) {
            return static::createWithArray($record);
        } else {
            $type = gettype($record);
            $type = $type === 'object' ? get_class($record) : $type;
            $msg = 'parameter 1 to '.static::class.'::make() must be ' .
                   'an instance of '.static::getInterfaceName().' or an array, not ' . $type;
            throw new \InvalidArgumentException($msg);
        }
    }

    /**
     * Initializes the ``AbstractResultRecord`` instance
     *
     * @param  string $dn
     */
    protected function initResultRecord(string $dn)
    {
        $this->dn = $dn;
    }

    /**
     * {@inheritdoc}
     */
    public function getDn() : string
    {
        return $this->dn;
    }
}

// vim: syntax=php sw=4 ts=4 et:
