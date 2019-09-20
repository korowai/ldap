<?php
/**
 * @file packages/ldaplib/Tests/Adapter/ExtLdap/LdapLinkOptionsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Tests\Adapter;

use PHPUnit\Framework\TestCase;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkOptions;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class LdapLinkOptions1YQ4YR18
{
    use LdapLinkOptions {
        configureLdapLinkOptions as public;
        configureLdapLinkOption as public;
    }
}

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdapLinkOptionsTest extends TestCase
{
    protected function getConfiguredResolver()
    {
        $resolver = new OptionsResolver();
        (new LdapLinkOptions1YQ4YR18())->configureLdapLinkOptions($resolver);
        return $resolver;
    }

    public function test__getOptionConstantName()
    {
        $no = new LdapLinkOptions1YQ4YR18();

        $this->assertSame('LDAP_OPT_DEREF', $no->getLdapLinkOptionConstantName('deref'));
        $this->assertSame('LDAP_OPT_SIZELIMIT', $no->getLdapLinkOptionConstantName('sizelimit'));
        $this->assertSame('LDAP_OPT_TIMELIMIT', $no->getLdapLinkOptionConstantName('timelimit'));
        $this->assertSame('LDAP_OPT_NETWORK_TIMEOUT', $no->getLdapLinkOptionConstantName('network_timeout'));
        $this->assertSame('LDAP_OPT_PROTOCOL_VERSION', $no->getLdapLinkOptionConstantName('protocol_version'));
        $this->assertSame('LDAP_OPT_ERROR_NUMBER', $no->getLdapLinkOptionConstantName('error_number'));
        $this->assertSame('LDAP_OPT_REFERRALS', $no->getLdapLinkOptionConstantName('referrals'));
        $this->assertSame('LDAP_OPT_RESTART', $no->getLdapLinkOptionConstantName('restart'));
        $this->assertSame('LDAP_OPT_HOST_NAME', $no->getLdapLinkOptionConstantName('host_name'));
        $this->assertSame('LDAP_OPT_ERROR_STRING', $no->getLdapLinkOptionConstantName('error_string'));
        $this->assertSame('LDAP_OPT_DIAGNOSTIC_MESSAGE', $no->getLdapLinkOptionConstantName('diagnostic_message'));
        $this->assertSame('LDAP_OPT_MATCHED_DN', $no->getLdapLinkOptionConstantName('matched_dn'));
        $this->assertSame('LDAP_OPT_SERVER_CONTROLS', $no->getLdapLinkOptionConstantName('server_controls'));
        $this->assertSame('LDAP_OPT_CLIENT_CONTROLS', $no->getLdapLinkOptionConstantName('client_controls'));

        if (PHP_VERSION_ID >= 70100) {
            // Session
            $this->assertSame('LDAP_OPT_X_KEEPALIVE_IDLE', $no->getLdapLinkOptionConstantName('keepalive_idle'));
            $this->assertSame('LDAP_OPT_X_KEEPALIVE_PROBES', $no->getLdapLinkOptionConstantName('keepalive_probes'));
            $this->assertSame('LDAP_OPT_X_KEEPALIVE_INTERVAL', $no->getLdapLinkOptionConstantName('keepalive_interval'));
        }

        // SASL
        $this->assertSame('LDAP_OPT_X_SASL_MECH', $no->getLdapLinkOptionConstantName('sasl_mech'));
        $this->assertSame('LDAP_OPT_X_SASL_REALM', $no->getLdapLinkOptionConstantName('sasl_realm'));
        $this->assertSame('LDAP_OPT_X_SASL_AUTHCID', $no->getLdapLinkOptionConstantName('sasl_authcid'));
        $this->assertSame('LDAP_OPT_X_SASL_AUTHZID', $no->getLdapLinkOptionConstantName('sasl_authzid'));

        if (PHP_VERSION_ID >= 70100) {
            // TLS (API_VERSION > 2000)
            $this->assertSame('LDAP_OPT_X_TLS_CACERTDIR', $no->getLdapLinkOptionConstantName('tls_cacertdir'));
            $this->assertSame('LDAP_OPT_X_TLS_CACERTFILE', $no->getLdapLinkOptionConstantName('tls_cacertfile'));
            $this->assertSame('LDAP_OPT_X_TLS_CERTFILE', $no->getLdapLinkOptionConstantName('tls_certfile'));
            $this->assertSame('LDAP_OPT_X_TLS_CIPHER_SUITE', $no->getLdapLinkOptionConstantName('tls_cipher_suite'));
            $this->assertSame('LDAP_OPT_X_TLS_CRLCHECK', $no->getLdapLinkOptionConstantName('tls_crlcheck'));
            $this->assertSame('LDAP_OPT_X_TLS_CRLFILE', $no->getLdapLinkOptionConstantName('tls_crlfile'));
            $this->assertSame('LDAP_OPT_X_TLS_DHFILE', $no->getLdapLinkOptionConstantName('tls_dhfile'));
            $this->assertSame('LDAP_OPT_X_TLS_KEYFILE', $no->getLdapLinkOptionConstantName('tls_keyfile'));
            $this->assertSame('LDAP_OPT_X_TLS_PROTOCOL_MIN', $no->getLdapLinkOptionConstantName('tls_protocol_min'));
            $this->assertSame('LDAP_OPT_X_TLS_RANDOM_FILE', $no->getLdapLinkOptionConstantName('tls_random_file'));
        }

        if (PHP_VERSION_ID >= 70050) {
            $this->assertSame('LDAP_OPT_X_TLS_REQUIRE_CERT', $no->getLdapLinkOptionConstantName('tls_require_cert'));
        }
    }

    public function test__getOptionConstantName__inexistend()
    {
        $no = new LdapLinkOptions1YQ4YR18();
        $this->assertNull($no->getLdapLinkOptionConstantName('inexistent'));
    }

    public function test__getOptionConstant()
    {
        $no = new LdapLinkOptions1YQ4YR18();

        $this->assertSame(LDAP_OPT_DEREF, $no->getLdapLinkOptionConstant('deref'));
        $this->assertSame(LDAP_OPT_SIZELIMIT, $no->getLdapLinkOptionConstant('sizelimit'));
        $this->assertSame(LDAP_OPT_TIMELIMIT, $no->getLdapLinkOptionConstant('timelimit'));
        $this->assertSame(LDAP_OPT_NETWORK_TIMEOUT, $no->getLdapLinkOptionConstant('network_timeout'));
        $this->assertSame(LDAP_OPT_PROTOCOL_VERSION, $no->getLdapLinkOptionConstant('protocol_version'));
        $this->assertSame(LDAP_OPT_ERROR_NUMBER, $no->getLdapLinkOptionConstant('error_number'));
        $this->assertSame(LDAP_OPT_REFERRALS, $no->getLdapLinkOptionConstant('referrals'));
        $this->assertSame(LDAP_OPT_RESTART, $no->getLdapLinkOptionConstant('restart'));
        $this->assertSame(LDAP_OPT_HOST_NAME, $no->getLdapLinkOptionConstant('host_name'));
        $this->assertSame(LDAP_OPT_ERROR_STRING, $no->getLdapLinkOptionConstant('error_string'));
        $this->assertSame(LDAP_OPT_DIAGNOSTIC_MESSAGE, $no->getLdapLinkOptionConstant('diagnostic_message'));
        $this->assertSame(LDAP_OPT_MATCHED_DN, $no->getLdapLinkOptionConstant('matched_dn'));
        $this->assertSame(LDAP_OPT_SERVER_CONTROLS, $no->getLdapLinkOptionConstant('server_controls'));
        $this->assertSame(LDAP_OPT_CLIENT_CONTROLS, $no->getLdapLinkOptionConstant('client_controls'));

        if (PHP_VERSION_ID >= 70100) {
            // Session
            $this->assertSame(LDAP_OPT_X_KEEPALIVE_IDLE, $no->getLdapLinkOptionConstant('keepalive_idle'));
            $this->assertSame(LDAP_OPT_X_KEEPALIVE_PROBES, $no->getLdapLinkOptionConstant('keepalive_probes'));
            $this->assertSame(LDAP_OPT_X_KEEPALIVE_INTERVAL, $no->getLdapLinkOptionConstant('keepalive_interval'));
        }

        // SASL
        $this->assertSame(LDAP_OPT_X_SASL_MECH, $no->getLdapLinkOptionConstant('sasl_mech'));
        $this->assertSame(LDAP_OPT_X_SASL_REALM, $no->getLdapLinkOptionConstant('sasl_realm'));
        $this->assertSame(LDAP_OPT_X_SASL_AUTHCID, $no->getLdapLinkOptionConstant('sasl_authcid'));
        $this->assertSame(LDAP_OPT_X_SASL_AUTHZID, $no->getLdapLinkOptionConstant('sasl_authzid'));

        if (PHP_VERSION_ID >= 70100) {
            // TLS (API_VERSION > 2000)
            $this->assertSame(LDAP_OPT_X_TLS_CACERTDIR, $no->getLdapLinkOptionConstant('tls_cacertdir'));
            $this->assertSame(LDAP_OPT_X_TLS_CACERTFILE, $no->getLdapLinkOptionConstant('tls_cacertfile'));
            $this->assertSame(LDAP_OPT_X_TLS_CERTFILE, $no->getLdapLinkOptionConstant('tls_certfile'));
            $this->assertSame(LDAP_OPT_X_TLS_CIPHER_SUITE, $no->getLdapLinkOptionConstant('tls_cipher_suite'));
            $this->assertSame(LDAP_OPT_X_TLS_CRLCHECK, $no->getLdapLinkOptionConstant('tls_crlcheck'));
            $this->assertSame(LDAP_OPT_X_TLS_CRLFILE, $no->getLdapLinkOptionConstant('tls_crlfile'));
            $this->assertSame(LDAP_OPT_X_TLS_DHFILE, $no->getLdapLinkOptionConstant('tls_dhfile'));
            $this->assertSame(LDAP_OPT_X_TLS_KEYFILE, $no->getLdapLinkOptionConstant('tls_keyfile'));
            $this->assertSame(LDAP_OPT_X_TLS_PROTOCOL_MIN, $no->getLdapLinkOptionConstant('tls_protocol_min'));
            $this->assertSame(LDAP_OPT_X_TLS_RANDOM_FILE, $no->getLdapLinkOptionConstant('tls_random_file'));
        }

        if (PHP_VERSION_ID >= 70050) {
            $this->assertSame(LDAP_OPT_X_TLS_REQUIRE_CERT, $no->getLdapLinkOptionConstant('tls_require_cert'));
        }
    }

    public function test__getOptionConstant_inexistend()
    {
        $no = new LdapLinkOptions1YQ4YR18();

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionMessage("Unknown option 'inexistent'");
        $this->expectExceptionCode(-1);
        $no->getLdapLinkOptionConstant('inexistent');
    }

    public function test__getLdapLinkOptionDeclarations()
    {
        $no = new LdapLinkOptions1YQ4YR18();

        $options =  $no->getLdapLinkOptionDeclarations();

        $v1 = ['never' => LDAP_DEREF_NEVER,
               'searching' => LDAP_DEREF_SEARCHING,
               'finding' => LDAP_DEREF_FINDING,
               'always' => LDAP_DEREF_ALWAYS];
        $v2 = ['none' => LDAP_OPT_X_TLS_CRL_NONE,
               'peer' => LDAP_OPT_X_TLS_CRL_PEER,
               'all' => LDAP_OPT_X_TLS_CRL_ALL];
        $v3 = ['never' => LDAP_OPT_X_TLS_NEVER,
               'hard' => LDAP_OPT_X_TLS_HARD,
               'demand' => LDAP_OPT_X_TLS_DEMAND,
               'allow' => LDAP_OPT_X_TLS_ALLOW,
               'try' => LDAP_OPT_X_TLS_TRY];

        $this->assertEquals(['types' => ['string','int'], 'constant' => 'LDAP_OPT_DEREF', 'values' => $v1], $options['deref']);
        $this->assertEquals(['types' => 'int',     'constant' => 'LDAP_OPT_SIZELIMIT'],              $options['sizelimit']);
        $this->assertEquals(['types' => 'int',     'constant' => 'LDAP_OPT_TIMELIMIT'],              $options['timelimit']);
        $this->assertEquals(['types' => 'int',     'constant' => 'LDAP_OPT_NETWORK_TIMEOUT'],        $options['network_timeout']);
        $this->assertEquals(['types' => 'int',     'constant' => 'LDAP_OPT_PROTOCOL_VERSION', 'default' => 3, 'values' => [2, 3]], $options['protocol_version']);
        $this->assertEquals(['types' => 'int',     'constant' => 'LDAP_OPT_ERROR_NUMBER'],           $options['error_number']);
        $this->assertEquals(['types' => 'bool',    'constant' => 'LDAP_OPT_REFERRALS'],              $options['referrals']);
        $this->assertEquals(['types' => 'bool',    'constant' => 'LDAP_OPT_RESTART'],                $options['restart']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_HOST_NAME'],              $options['host_name']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_ERROR_STRING'],           $options['error_string']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_DIAGNOSTIC_MESSAGE'],     $options['diagnostic_message']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_MATCHED_DN'],             $options['matched_dn']);
        $this->assertEquals(['types' => 'array',   'constant' => 'LDAP_OPT_SERVER_CONTROLS'],        $options['server_controls']);
        $this->assertEquals(['types' => 'array',   'constant' => 'LDAP_OPT_CLIENT_CONTROLS'],        $options['client_controls']);

        if (PHP_VERSION_ID >= 70100) {
            // Session
            $this->assertEquals(['types' => 'int', 'constant' => 'LDAP_OPT_X_KEEPALIVE_IDLE'],       $options['keepalive_idle']);
            $this->assertEquals(['types' => 'int', 'constant' => 'LDAP_OPT_X_KEEPALIVE_PROBES'],     $options['keepalive_probes']);
            $this->assertEquals(['types' => 'int', 'constant' => 'LDAP_OPT_X_KEEPALIVE_INTERVAL'],   $options['keepalive_interval']);
        }

        // SASL
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_SASL_MECH'],            $options['sasl_mech']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_SASL_REALM'],           $options['sasl_realm']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_SASL_AUTHCID'],         $options['sasl_authcid']);
        $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_SASL_AUTHZID'],         $options['sasl_authzid']);

        if (PHP_VERSION_ID >= 70100) {
            // TLS (API_VERSION > 2000)
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_CACERTDIR'],    $options['tls_cacertdir']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_CACERTFILE'],   $options['tls_cacertfile']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_CERTFILE'],     $options['tls_certfile']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_CIPHER_SUITE'], $options['tls_cipher_suite']);
            $this->assertEquals(['types' => ['string', 'int'], 'constant' => 'LDAP_OPT_X_TLS_CRLCHECK', 'values' => $v2], $options['tls_crlcheck']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_CRLFILE'],      $options['tls_crlfile']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_DHFILE'],       $options['tls_dhfile']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_KEYFILE'],      $options['tls_keyfile']);
            $this->assertEquals(['types' => 'int',     'constant' => 'LDAP_OPT_X_TLS_PROTOCOL_MIN'], $options['tls_protocol_min']);
            $this->assertEquals(['types' => 'string',  'constant' => 'LDAP_OPT_X_TLS_RANDOM_FILE'],  $options['tls_random_file']);
        }

        if (PHP_VERSION_ID >= 70050) {
            $this->assertEquals(['types' => ['string', 'int'], 'constant' => 'LDAP_OPT_X_TLS_REQUIRE_CERT', 'values' => $v3], $options['tls_require_cert']);
        }
    }

    public function test__configureLdapLinkOptions()
    {
        $resolver = $this->getConfiguredResolver();

        $this->assertTrue($resolver->isDefined('deref'));
        $this->assertTrue($resolver->isDefined('sizelimit'));
        $this->assertTrue($resolver->isDefined('timelimit'));
        $this->assertTrue($resolver->isDefined('network_timeout'));
        $this->assertTrue($resolver->isDefined('protocol_version'));
        $this->assertTrue($resolver->isDefined('error_number'));
        $this->assertTrue($resolver->isDefined('referrals'));
        $this->assertTrue($resolver->isDefined('restart'));
        $this->assertTrue($resolver->isDefined('host_name'));
        $this->assertTrue($resolver->isDefined('error_string'));
        $this->assertTrue($resolver->isDefined('diagnostic_message'));
        $this->assertTrue($resolver->isDefined('matched_dn'));
        $this->assertTrue($resolver->isDefined('server_controls'));
        $this->assertTrue($resolver->isDefined('client_controls'));

        if (PHP_VERSION_ID >= 70100) {
            // Session
            $this->assertTrue($resolver->isDefined('keepalive_idle'));
            $this->assertTrue($resolver->isDefined('keepalive_probes'));
            $this->assertTrue($resolver->isDefined('keepalive_interval'));
        }

        // SASL
        $this->assertTrue($resolver->isDefined('sasl_mech'));
        $this->assertTrue($resolver->isDefined('sasl_realm'));
        $this->assertTrue($resolver->isDefined('sasl_authcid'));
        $this->assertTrue($resolver->isDefined('sasl_authzid'));

        if (PHP_VERSION_ID >= 70100) {
            // TLS (API_VERSION > 2000)
            $this->assertTrue($resolver->isDefined('tls_cacertdir'));
            $this->assertTrue($resolver->isDefined('tls_cacertfile'));
            $this->assertTrue($resolver->isDefined('tls_certfile'));
            $this->assertTrue($resolver->isDefined('tls_cipher_suite'));
            $this->assertTrue($resolver->isDefined('tls_crlcheck'));
            $this->assertTrue($resolver->isDefined('tls_crlfile'));
            $this->assertTrue($resolver->isDefined('tls_dhfile'));
            $this->assertTrue($resolver->isDefined('tls_keyfile'));
            $this->assertTrue($resolver->isDefined('tls_protocol_min'));
            $this->assertTrue($resolver->isDefined('tls_random_file'));
        }

        if (PHP_VERSION_ID >= 70050) {
            $this->assertTrue($resolver->isDefined('tls_require_cert'));
        }
    }

    public function test__configureLdapLinkOptions__Defaults()
    {
        $resolver = $this->getConfiguredResolver();
        $this->assertSame(['protocol_version' => 3], $resolver->resolve([]));
    }

    public function test__configureLdapLinkOption__WithNonArrayValues()
    {
        $decl = [
            'types' => 'string',
            'values' => function ($s) { return substr($s, 0, 3) === 'foo'; }
        ];
        $resolver = new OptionsResolver();
        $options = new LdapLinkOptions1YQ4YR18();
        $options->configureLdapLinkOption($resolver, 'foo', $decl);

        $this->assertEquals(['foo' => 'foomatic'], $resolver->resolve(['foo' => 'foomatic']));

        $this->expectException(InvalidOptionsException::class);
        $resolver->resolve(['foo' => 'barmatic']);
    }

    public function test__option__deref()
    {
        $resolver = $this->getConfiguredResolver();
        $this->assertNull(($resolver->resolve([]))['deref'] ?? null);

        // never
        $this->assertEquals(LDAP_DEREF_NEVER, $resolver->resolve(['deref' => LDAP_DEREF_NEVER])['deref']);
        $this->assertEquals(LDAP_DEREF_NEVER, $resolver->resolve(['deref' => 'never'])['deref']);
        // searching
        $this->assertEquals(LDAP_DEREF_SEARCHING, $resolver->resolve(['deref' => LDAP_DEREF_SEARCHING])['deref']);
        $this->assertEquals(LDAP_DEREF_SEARCHING, $resolver->resolve(['deref' => 'searching'])['deref']);
        // finding
        $this->assertEquals(LDAP_DEREF_FINDING, $resolver->resolve(['deref' => LDAP_DEREF_FINDING])['deref']);
        $this->assertEquals(LDAP_DEREF_FINDING, $resolver->resolve(['deref' => 'finding'])['deref']);
        // always
        $this->assertEquals(LDAP_DEREF_ALWAYS, $resolver->resolve(['deref' => LDAP_DEREF_ALWAYS])['deref']);
        $this->assertEquals(LDAP_DEREF_ALWAYS, $resolver->resolve(['deref' => 'always'])['deref']);
    }

    public function test__option__tls_crlcheck()
    {
        $resolver = $this->getConfiguredResolver();
        $this->assertNull(($resolver->resolve([]))['tls_crlcheck'] ?? null);

        if (PHP_VERSION_ID >= 70100) {
            // none
            $this->assertEquals(LDAP_OPT_X_TLS_CRL_NONE, $resolver->resolve(['tls_crlcheck' => LDAP_OPT_X_TLS_CRL_NONE])['tls_crlcheck']);
            $this->assertEquals(LDAP_OPT_X_TLS_CRL_NONE, $resolver->resolve(['tls_crlcheck' => 'none'])['tls_crlcheck']);
            // peer
            $this->assertEquals(LDAP_OPT_X_TLS_CRL_PEER, $resolver->resolve(['tls_crlcheck' => LDAP_OPT_X_TLS_CRL_PEER])['tls_crlcheck']);
            $this->assertEquals(LDAP_OPT_X_TLS_CRL_PEER, $resolver->resolve(['tls_crlcheck' => 'peer'])['tls_crlcheck']);
            // all
            $this->assertEquals(LDAP_OPT_X_TLS_CRL_ALL, $resolver->resolve(['tls_crlcheck' => LDAP_OPT_X_TLS_CRL_ALL])['tls_crlcheck']);
            $this->assertEquals(LDAP_OPT_X_TLS_CRL_ALL, $resolver->resolve(['tls_crlcheck' => 'all'])['tls_crlcheck']);
        }
    }

    public function test__option__tls_require_cert()
    {
        $resolver = $this->getConfiguredResolver();
        $this->assertNull(($resolver->resolve([]))['tls_require_cert'] ?? null);

        if (PHP_VERSION_ID >= 70050) {
            // never
            $this->assertEquals(LDAP_OPT_X_TLS_NEVER, $resolver->resolve(['tls_require_cert' => LDAP_OPT_X_TLS_NEVER])['tls_require_cert']);
            $this->assertEquals(LDAP_OPT_X_TLS_NEVER, $resolver->resolve(['tls_require_cert' => 'never'])['tls_require_cert']);
            // hard
            $this->assertEquals(LDAP_OPT_X_TLS_HARD, $resolver->resolve(['tls_require_cert' => LDAP_OPT_X_TLS_HARD])['tls_require_cert']);
            $this->assertEquals(LDAP_OPT_X_TLS_HARD, $resolver->resolve(['tls_require_cert' => 'hard'])['tls_require_cert']);
            // demand
            $this->assertEquals(LDAP_OPT_X_TLS_DEMAND, $resolver->resolve(['tls_require_cert' => LDAP_OPT_X_TLS_DEMAND])['tls_require_cert']);
            $this->assertEquals(LDAP_OPT_X_TLS_DEMAND, $resolver->resolve(['tls_require_cert' => 'demand'])['tls_require_cert']);
            // allow
            $this->assertEquals(LDAP_OPT_X_TLS_ALLOW, $resolver->resolve(['tls_require_cert' => LDAP_OPT_X_TLS_ALLOW])['tls_require_cert']);
            $this->assertEquals(LDAP_OPT_X_TLS_ALLOW, $resolver->resolve(['tls_require_cert' => 'allow'])['tls_require_cert']);
            // try
            $this->assertEquals(LDAP_OPT_X_TLS_TRY, $resolver->resolve(['tls_require_cert' => LDAP_OPT_X_TLS_TRY])['tls_require_cert']);
            $this->assertEquals(LDAP_OPT_X_TLS_TRY, $resolver->resolve(['tls_require_cert' => 'try'])['tls_require_cert']);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
