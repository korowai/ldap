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

use Korowai\Lib\Ldap\Adapter\AbstractSearchQuery;
use Korowai\Lib\Ldap\Adapter\ResultInterface;

use function Korowai\Lib\Context\with;
use function Korowai\Lib\Error\emptyErrorHandler;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class SearchQuery extends AbstractSearchQuery
{
    use EnsureLdapLink;
    use LastLdapException;

    /** @var LdapLink */
    private $link;

    /**
     * Constructs SearchQuery
     */
    public function __construct(LdapLink $link, string $base_dn, string $filter, array $options = [])
    {
        $this->link  = $link;
        parent::__construct($base_dn, $filter, $options);
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

    protected static function getDerefOption(array $options)
    {
        if (isset($options['deref'])) {
            return constant('LDAP_DEREF_' . strtoupper($options['deref']));
        } else {
            return LDAP_DEREF_NEVER;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteQuery() : ResultInterface
    {
        $options = $this->getOptions();
        $scope = strtolower(isset($options['scope']) ? $options['scope'] : 'sub');
        switch ($scope) {
            case 'base':
                $func = 'read';
                break;
            case 'one':
                $func = 'list';
                break;
            case 'sub':
                $func = 'search';
                break;
            default:
                // This should be actualy covered by OptionsResolver in AbstractSearchQuery::__construct()
                throw new \RuntimeException(sprintf('Unsupported search scope "%s"', $options['scope']));
        }

        static::ensureLdapLink($this->getLink());
        return with(emptyErrorHandler())(function ($eh) use ($func) {
            return $this->doExecuteQueryImpl($func);
        });
    }

    private function doExecuteQueryImpl($func)
    {
        $options = $this->getOptions();
        $result = call_user_func(
            [$this->getLink(), $func],
            $this->getBaseDn(),
            $this->getFilter(),
            $options['attributes'],
            $options['attrsOnly'],
            $options['sizeLimit'],
            $options['timeLimit'],
            static::getDerefOption($options)
        );
        if (false === $result) {
            throw static::lastLdapException($this->getLink());
        }
        return $result;
    }


    protected function configureOptionsResolver(OptionsResolver $resolver)
    {
        return parent::configureOptionsResolver($resolver);
    }
}

// vim: syntax=php sw=4 ts=4 et:
