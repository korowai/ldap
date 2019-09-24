<?php
/**
 * @file src/Korowai/Lib/Ldap/Adapter/ResultReferenceIteratorInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

/**
 * Iterates through references returned by an ldap search query.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ResultReferenceIteratorInterface extends \Iterator
{
}

// vim: syntax=php sw=4 ts=4 et:
