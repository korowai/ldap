<?php
/**
 * @file src/Korowai/Lib/Ldap/Ldap.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 * @package korowai\ldaplib
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

use Korowai\Lib\Ldap\Adapter\AdapterInterface;
use Korowai\Lib\Ldap\Adapter\AdapterFactoryInterface;
use Korowai\Lib\Ldap\Adapter\BindingInterface;
use Korowai\Lib\Ldap\Adapter\EntryManagerInterface;
use Korowai\Lib\Ldap\Adapter\SearchQueryInterface;
use Korowai\Lib\Ldap\Adapter\CompareQueryInterface;
use Korowai\Lib\Ldap\Adapter\ResultInterface;

use \InvalidArgumentException;

/**
 * A facade to ldap component. Creates connection, binds, reads from and writes
 * to ldap.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Ldap extends AbstractLdap
{
    protected static $defaultAdapterFactory = '\Korowai\Lib\Ldap\Adapter\ExtLdap\AdapterFactory';

    /** @var AdapterInterface */
    private $adapter;

    /**
     *
     * @param array $config
     * @param string $factoryClass
     *
     * @throws InvalidArgumentException
     */
    public static function createWithConfig(array $config = array(), string $factoryClass = null)
    {
        if (!isset($factoryClass)) {
            $factoryClass = static::$defaultAdapterFactory;
        } else {
            static::checkFactoryClassArg($factoryClass, __METHOD__, 2);
        }
        $factory = new $factoryClass();
        $factory->configure($config);
        return static::createWithAdapterFactory($factory);
    }

    public static function createWithAdapterFactory(AdapterFactoryInterface $factory)
    {
        $adapter = $factory->createAdapter();
        return new Ldap($adapter);
    }

    /**
     * Create new Ldap instance
     *
     * @param Adapter $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdapter() : AdapterInterface
    {
        return $this->adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function getBinding() : BindingInterface
    {
        return $this->adapter->getBinding();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntryManager() : EntryManagerInterface
    {
        return $this->adapter->getEntryManager();
    }

    /**
     * {@inheritdoc}
     */
    public function bind(string $dn = null, string $password = null)
    {
        $args = @func_get_args();
        return $this->getBinding()->bind(...$args);
    }

    /**
     * {@inheritdoc}
     */
    public function unbind()
    {
        return $this->getBinding()->unbind();
    }

    /**
     * {@inheritdoc}
     */
    public function isBound() : bool
    {
        return $this->getBinding()->isBound();
    }

    /**
     * {@inheritdoc}
     */
    public function add(EntryInterface $entry)
    {
        return $this->getEntryManager()->add($entry);
    }

    /**
     * {@inheritdoc}
     */
    public function update(EntryInterface $entry)
    {
        return $this->getEntryManager()->update($entry);
    }

    /**
     * {@inheritdoc}
     */
    public function rename(EntryInterface $entry, string $newRdn, bool $deleteOldRdn = true)
    {
        return $this->getEntryManager()->rename($entry, $newRdn, $deleteOldRdn);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(EntryInterface $entry)
    {
        return $this->getEntryManager()->delete($entry);
    }

    /**
     * {@inheritdoc}
     */
    public function createSearchQuery(string $base_dn, string $filter, array $options = array()) : SearchQueryInterface
    {
        return $this->getAdapter()->createSearchQuery($base_dn, $filter, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function createCompareQuery(string $dn, string $attribute, string $value) : CompareQueryInterface
    {
        return $this->getAdapter()->createCompareQuery($dn, $attribute, $value);
    }

    protected static function checkFactoryClassArg($factoryClass, $method, $argno)
    {
        $msg_pre = "Invalid argument $argno to $method";
        if (!class_exists($factoryClass)) {
            $msg = $msg_pre . ": $factoryClass is not a name of existing class";
            throw new InvalidArgumentException($msg);
        }
        if (!is_subclass_of($factoryClass, AdapterFactoryInterface::class)) {
            $msg = $msg_pre . ": $factoryClass is not an implementation of ". AdapterFactoryInterface::class;
            throw new InvalidArgumentException($msg);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
