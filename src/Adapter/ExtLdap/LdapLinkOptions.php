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

use Korowai\Lib\Ldap\Exception\LdapException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdapLinkOptions
{
    private static $ldapLinkOptionDeclarations = [
        'deref'               => ['types' => ['string', 'int'],
                                  'constant' => 'LDAP_OPT_DEREF',
                                  'values' => ['never' => LDAP_DEREF_NEVER,
                                               'searching'=> LDAP_DEREF_SEARCHING,
                                               'finding' => LDAP_DEREF_FINDING,
                                               'always' => LDAP_DEREF_ALWAYS]],
        'sizelimit'           => ['types' => 'int',    'constant' => 'LDAP_OPT_SIZELIMIT'],
        'timelimit'           => ['types' => 'int',    'constant' => 'LDAP_OPT_TIMELIMIT'],
        'network_timeout'     => ['types' => 'int',    'constant' => 'LDAP_OPT_NETWORK_TIMEOUT'],
        'protocol_version'    => ['types' => 'int',    'constant' => 'LDAP_OPT_PROTOCOL_VERSION', 'default' => 3,
                                  'values' => [2, 3]],
        'error_number'        => ['types' => 'int',    'constant' => 'LDAP_OPT_ERROR_NUMBER'],
        'referrals'           => ['types' => 'bool',   'constant' => 'LDAP_OPT_REFERRALS'],
        'restart'             => ['types' => 'bool',   'constant' => 'LDAP_OPT_RESTART'],
        'host_name'           => ['types' => 'string', 'constant' => 'LDAP_OPT_HOST_NAME'],
        'error_string'        => ['types' => 'string', 'constant' => 'LDAP_OPT_ERROR_STRING'],
        'diagnostic_message'  => ['types' => 'string', 'constant' => 'LDAP_OPT_DIAGNOSTIC_MESSAGE'],
        'matched_dn'          => ['types' => 'string', 'constant' => 'LDAP_OPT_MATCHED_DN'],
        'server_controls'     => ['types' => 'array',  'constant' => 'LDAP_OPT_SERVER_CONTROLS'],
        'client_controls'     => ['types' => 'array',  'constant' => 'LDAP_OPT_CLIENT_CONTROLS'],

        'keepalive_idle'      => ['types' => 'int',    'constant' => 'LDAP_OPT_X_KEEPALIVE_IDLE'],
        'keepalive_probes'    => ['types' => 'int',    'constant' => 'LDAP_OPT_X_KEEPALIVE_PROBES'],
        'keepalive_interval'  => ['types' => 'int',    'constant' => 'LDAP_OPT_X_KEEPALIVE_INTERVAL'],

        'sasl_mech'           => ['types' => 'string', 'constant' => 'LDAP_OPT_X_SASL_MECH'],
        'sasl_realm'          => ['types' => 'string', 'constant' => 'LDAP_OPT_X_SASL_REALM'],
        'sasl_authcid'        => ['types' => 'string', 'constant' => 'LDAP_OPT_X_SASL_AUTHCID'],
        'sasl_authzid'        => ['types' => 'string', 'constant' => 'LDAP_OPT_X_SASL_AUTHZID'],
        // PHP >= 7.1.0
        'tls_cacertdir'       => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_CACERTDIR'],
        'tls_cacertfile'      => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_CACERTFILE'],
        'tls_certfile'        => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_CERTFILE'],
        'tls_cipher_suite'    => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_CIPHER_SUITE'],
        'tls_crlcheck'        => ['types' => ['string', 'int'],
                                  'constant' => 'LDAP_OPT_X_TLS_CRLCHECK',
                                  'values' => ['none' => LDAP_OPT_X_TLS_CRL_NONE,
                                               'peer' => LDAP_OPT_X_TLS_CRL_PEER,
                                               'all' => LDAP_OPT_X_TLS_CRL_ALL]],
        'tls_crlfile'         => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_CRLFILE'],
        'tls_dhfile'          => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_DHFILE'],
        'tls_keyfile'         => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_KEYFILE'],
        'tls_protocol_min'    => ['types' => 'int',    'constant' => 'LDAP_OPT_X_TLS_PROTOCOL_MIN'],
        'tls_random_file'     => ['types' => 'string', 'constant' => 'LDAP_OPT_X_TLS_RANDOM_FILE'],
        // PHP >= 7.0.5
        'tls_require_cert'    => ['types' => ['string', 'int'],
                                  'constant' => 'LDAP_OPT_X_TLS_REQUIRE_CERT',
                                  'values' => ['never' => LDAP_OPT_X_TLS_NEVER,
                                               'hard' => LDAP_OPT_X_TLS_HARD,
                                               'demand' => LDAP_OPT_X_TLS_DEMAND,
                                               'allow' => LDAP_OPT_X_TLS_ALLOW,
                                               'try' => LDAP_OPT_X_TLS_TRY]],
    ];

    /**
     * Returns name of an ext-ldap option constant for a given option name
     * @return string Name of the ext-ldap constant
     */
    public function getLdapLinkOptionConstantName($optionName)
    {
        if (!isset(self::$ldapLinkOptionDeclarations[$optionName]['constant'])) {
            return null;
        }
        $name = self::$ldapLinkOptionDeclarations[$optionName]['constant'];
        return defined($name) ? $name : null;
    }

    /**
     * Returns value of an ext-ldap option constant for a given option name
     *
     * @throws LdapException
     * @return mixed Value of the ext-ldap constant
     */
    public function getLdapLinkOptionConstant($name)
    {
        $constantName = $this->getLdapLinkOptionConstantName($name);

        if (!$constantName) {
            throw new LdapException("Unknown option '$name'", -1);
        }

        return constant($constantName);
    }

    /**
     * Returns declarations of options, mainly for use by ``configureLdapLinkOptions()``
     * @return array Declarations
     */
    public function getLdapLinkOptionDeclarations()
    {
        static $existingOptions;
        if (!isset($existingOptions)) {
            $existingOptions = array_filter(
                self::$ldapLinkOptionDeclarations,
                [$this, 'getLdapLinkOptionConstantName'],
                ARRAY_FILTER_USE_KEY
            );
        }
        return $existingOptions;
    }

    /**
     * Configures symfony's  OptionsResolver to parse LdapLink options
     */
    protected function configureLdapLinkOptions(OptionsResolver $resolver)
    {
        $ldapLinkOptionDeclarations = $this->getLdapLinkOptionDeclarations();
        foreach ($ldapLinkOptionDeclarations as $name => $decl) {
            $this->configureLdapLinkOption($resolver, $name, $decl);
        }
    }

    /**
     * Configures symfony's OptionResolver for a single option.
     */
    protected function configureLdapLinkOption(OptionsResolver $resolver, string $name, $decl)
    {
        if (array_key_exists('default', $decl)) {
            $resolver->setDefault($name, $decl['default']);
        } else {
            $resolver->setDefined($name);
        }
        $resolver->setAllowedTypes($name, $decl['types']);
        if (array_key_exists('values', $decl)) {
            $this->setLdapLinkOptionAllowedValues($resolver, $name, $decl['values']);
        }
    }

    protected function setLdapLinkOptionAllowedValues(OptionsResolver $resolver, string $name, $allowed)
    {
        if (is_array($allowed)) {
            $this->setLdapLinkOptionAllowedValuesArray($resolver, $name, $allowed);
        } else {
            // it can be a callback, for example...
            $resolver->setAllowedValues($name, $allowed);
        }
    }

    protected function setLdapLinkOptionAllowedValuesArray(OptionsResolver $resolver, string $name, array $allowed)
    {
        $keys = array_keys($allowed);
        if ($keys != range(0, count($allowed)-1)) {
            // Associative array: array keys and values are the resolver's allowed values.
            $resolver->setAllowedValues($name, array_merge($keys, array_values($allowed)));
            $resolver->setNormalizer($name, function (Options $options, $value) use ($allowed) {
                return $allowed[$value] ?? $value;
            });
        } else {
            $resolver->setAllowedValues($name, $allowed);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
