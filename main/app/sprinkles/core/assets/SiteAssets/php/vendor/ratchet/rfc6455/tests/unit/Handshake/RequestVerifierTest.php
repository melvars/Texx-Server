<?php

namespace Ratchet\RFC6455\Test\Unit\Handshake;

use Ratchet\RFC6455\Handshake\RequestVerifier;

/**
 * @covers Ratchet\RFC6455\Handshake\RequestVerifier
 */
class RequestVerifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RequestVerifier
     */
    protected $_v;

    public function setUp() {
        $this->_v = new RequestVerifier();
    }

    public static function methodProvider() {
        return array(
            array(TRUE, 'GET'),
            array(TRUE, 'get'),
            array(TRUE, 'Get'),
            array(FALSE, 'POST'),
            array(FALSE, 'DELETE'),
            array(FALSE, 'PUT'),
            array(FALSE, 'PATCH')
        );
    }

    /**
     * @dataProvider methodProvider
     */
    public function testMethodMustBeGet($result, $in) {
        $this->assertEquals($result, $this->_v->verifyMethod($in));
    }

    public static function httpVersionProvider() {
        return array(
            array(TRUE, 1.1),
            array(TRUE, '1.1'),
            array(TRUE, 1.2),
            array(TRUE, '1.2'),
            array(TRUE, 2),
            array(TRUE, '2'),
            array(TRUE, '2.0'),
            array(FALSE, '1.0'),
            array(FALSE, 1),
            array(FALSE, '0.9'),
            array(FALSE, ''),
            array(FALSE, 'hello')
        );
    }

    /**
     * @dataProvider httpVersionProvider
     */
    public function testHttpVersionIsAtLeast1Point1($expected, $in) {
        $this->assertEquals($expected, $this->_v->verifyHTTPVersion($in));
    }

    public static function uRIProvider() {
        return array(
            array(TRUE, '/chat'),
            array(TRUE, '/hello/world?key=val'),
            array(FALSE, '/chat#bad'),
            array(FALSE, 'nope'),
            array(FALSE, '/ ಠ_ಠ '),
            array(FALSE, '/✖')
        );
    }

    /**
     * @dataProvider URIProvider
     */
    public function testRequestUri($expected, $in) {
        $this->assertEquals($expected, $this->_v->verifyRequestURI($in));
    }

    public static function hostProvider() {
        return array(
            array(TRUE, ['server.example.com']),
            array(FALSE, [])
        );
    }

    /**
     * @dataProvider HostProvider
     */
    public function testVerifyHostIsSet($expected, $in) {
        $this->assertEquals($expected, $this->_v->verifyHost($in));
    }

    public static function upgradeProvider() {
        return array(
            array(TRUE, ['websocket']),
            array(TRUE, ['Websocket']),
            array(TRUE, ['webSocket']),
            array(FALSE, []),
            array(FALSE, [''])
        );
    }

    /**
     * @dataProvider upgradeProvider
     */
    public function testVerifyUpgradeIsWebSocket($expected, $val) {
        $this->assertEquals($expected, $this->_v->verifyUpgradeRequest($val));
    }

    public static function connectionProvider() {
        return array(
            array(TRUE, ['Upgrade']),
            array(TRUE, ['upgrade']),
            array(TRUE, ['keep-alive', 'Upgrade']),
            array(TRUE, ['Upgrade', 'keep-alive']),
            array(TRUE, ['keep-alive', 'Upgrade', 'something']),
            // as seen in Firefox 47.0.1 - see https://github.com/ratchetphp/RFC6455/issues/14
            array(TRUE, ['keep-alive, Upgrade']),
            array(TRUE, ['Upgrade, keep-alive']),
            array(TRUE, ['keep-alive, Upgrade, something']),
            array(TRUE, ['keep-alive, Upgrade', 'something']),
            array(FALSE, ['']),
            array(FALSE, [])
        );
    }

    /**
     * @dataProvider connectionProvider
     */
    public function testConnectionHeaderVerification($expected, $val) {
        $this->assertEquals($expected, $this->_v->verifyConnection($val));
    }

    public static function keyProvider() {
        return array(
            array(TRUE, ['hkfa1L7uwN6DCo4IS3iWAw==']),
            array(TRUE, ['765vVoQpKSGJwPzJIMM2GA==']),
            array(TRUE, ['AQIDBAUGBwgJCgsMDQ4PEC==']),
            array(TRUE, ['axa2B/Yz2CdpfQAY2Q5P7w==']),
            array(FALSE, [0]),
            array(FALSE, ['Hello World']),
            array(FALSE, ['1234567890123456']),
            array(FALSE, ['123456789012345678901234']),
            array(TRUE, [base64_encode('UTF8allthngs+✓')]),
            array(TRUE, ['dGhlIHNhbXBsZSBub25jZQ==']),
            array(FALSE, []),
            array(FALSE, ['dGhlIHNhbXBsZSBub25jZQ==', 'Some other value']),
            array(FALSE, ['Some other value', 'dGhlIHNhbXBsZSBub25jZQ=='])
        );
    }

    /**
     * @dataProvider keyProvider
     */
    public function testKeyIsBase64Encoded16BitNonce($expected, $val) {
        $this->assertEquals($expected, $this->_v->verifyKey($val));
    }

    public static function versionProvider() {
        return array(
            array(TRUE, [13]),
            array(TRUE, ['13']),
            array(FALSE, [12]),
            array(FALSE, [14]),
            array(FALSE, ['14']),
            array(FALSE, ['hi']),
            array(FALSE, ['']),
            array(FALSE, [])
        );
    }

    /**
     * @dataProvider versionProvider
     */
    public function testVersionEquals13($expected, $in) {
        $this->assertEquals($expected, $this->_v->verifyVersion($in));
    }
}