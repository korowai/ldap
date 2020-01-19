<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Lib\Ldap\Adapter\EntryManagerInterface;
use Korowai\Lib\Ldap\EntryInterface;

use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\emptyErrorHandler;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class EntryManager implements EntryManagerInterface
{
    use EnsureLdapLink;
    use LastLdapException;

    private $link;

    /**
     * Constructs EntryManager
     */
    public function __construct(LdapLink $link)
    {
        $this->link = $link;
    }

    /**
     * Returns a link resource
     *
     * @return resource
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * {@inheritdoc}
     *
     * Invokes ldap_add().
     */
    public function add(EntryInterface $entry)
    {
        return $this->callImplMethod('addImpl', $entry);
    }

    /**
     * {@inheritdoc}
     *
     * Invokes ldap_modify()
     */
    public function update(EntryInterface $entry)
    {
        return $this->callImplMethod('updateImpl', $entry);
    }

    /**
     * {@inheritdoc}
     *
     * Invokes ldap_rename()
     */
    public function rename(EntryInterface $entry, string $newRdn, bool $deleteOldRdn = true)
    {
        return $this->callImplMethod('renameImpl', $entry, $newRdn, $deleteOldRdn);
    }

    /**
     * {@inheritdoc}
     *
     * Invokes ldap_delete()
     */
    public function delete(EntryInterface $entry)
    {
        return $this->callImplMethod('deleteImpl', $entry);
    }

    /**
     * @internal
     */
    private function callImplMethod($name, ...$args)
    {
        static::ensureLdapLink($this->link);
        return with(emptyErrorHandler())(function ($eh) use ($name, $args) {
            return call_user_func_array([$this, $name], $args);
        });
    }

    /**
     * @internal
     */
    private function addImpl(EntryInterface $entry)
    {
        if (!$this->getLink()->add($entry->getDn(), $entry->getAttributes())) {
            throw static::lastLdapException($this->link);
        }
    }

    /**
     * @internal
     */
    public function updateImpl(EntryInterface $entry)
    {
        if (!$this->getLink()->modify($entry->getDn(), $entry->getAttributes())) {
            throw static::lastLdapException($this->link);
        }
    }

    /**
     * {@inheritdoc}
     *
     * Invokes ldap_rename()
     */
    public function renameImpl(EntryInterface $entry, string $newRdn, bool $deleteOldRdn = true)
    {
        if (!$this->getLink()->rename($entry->getDn(), $newRdn, null, $deleteOldRdn)) {
            throw static::lastLdapException($this->link);
        }
    }

    /**
     * {@inheritdoc}
     *
     * Invokes ldap_delete()
     */
    public function deleteImpl(EntryInterface $entry)
    {
        if (!$this->getLink()->delete($entry->getDn())) {
            throw static::lastLdapException($this->link);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
