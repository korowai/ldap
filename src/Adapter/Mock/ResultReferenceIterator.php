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

use Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferenceIterator extends AbstractResultIterator implements ResultReferenceIteratorInterface
{
    /**
     * Constructs ResultReferenceIterator
     *
     * @param Result $result            The ldap search result which provides
     *                                  first entry in the entry chain
     */
    public function __construct(Result $result)
    {
        parent::__construct($result);
    }

    protected function getMethodForCurrent()
    {
        return 'references_current';
    }

    protected function getMethodForNext()
    {
        return 'references_next';
    }

    protected function getMethodForReset()
    {
        return 'references_reset';
    }
}

// vim: syntax=php sw=4 ts=4 et:
