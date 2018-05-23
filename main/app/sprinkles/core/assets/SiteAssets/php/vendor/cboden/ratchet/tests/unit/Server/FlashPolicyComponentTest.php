<?php

namespace Ratchet\Application\Server;

use Ratchet\Server\FlashPolicy;

/**
 * @covers Ratchet\Server\FlashPolicy
 */
class FlashPolicyTest extends \PHPUnit_Framework_TestCase
{

    protected $_policy;

    public function setUp() {
        $this->_policy = new FlashPolicy();
    }

    public function testPolicyRender() {
        $this->_policy->setSiteControl('all');
        $this->_policy->addAllowedAccess('example.com', '*');
        $this->_policy->addAllowedAccess('dev.example.com', '*');

        $this->assertInstanceOf('SimpleXMLElement', $this->_policy->renderPolicy());
    }

    public function testInvalidPolicyReader() {
        $this->setExpectedException('UnexpectedValueException');
        $this->_policy->renderPolicy();
    }

    public function testInvalidDomainPolicyReader() {
        $this->setExpectedException('UnexpectedValueException');
        $this->_policy->setSiteControl('all');
        $this->_policy->addAllowedAccess('dev.example.*', '*');
        $this->_policy->renderPolicy();
    }

    /**
     * @dataProvider siteControl
     */
    public function testSiteControlValidation($accept, $permittedCrossDomainPolicies) {
        $this->assertEquals($accept, $this->_policy->validateSiteControl($permittedCrossDomainPolicies));
    }

    public static function siteControl() {
        return array(
            array(TRUE, 'all')
        , array(TRUE, 'none')
        , array(TRUE, 'master-only')
        , array(FALSE, 'by-content-type')
        , array(FALSE, 'by-ftp-filename')
        , array(FALSE, '')
        , array(FALSE, 'all ')
        , array(FALSE, 'asdf')
        , array(FALSE, '@893830')
        , array(FALSE, '*')
        );
    }

    /**
     * @dataProvider URI
     */
    public function testDomainValidation($accept, $domain) {
        $this->assertEquals($accept, $this->_policy->validateDomain($domain));
    }

    public static function URI() {
        return array(
            array(TRUE, '*')
        , array(TRUE, 'example.com')
        , array(TRUE, 'exam-ple.com')
        , array(TRUE, '*.example.com')
        , array(TRUE, 'www.example.com')
        , array(TRUE, 'dev.dev.example.com')
        , array(TRUE, 'http://example.com')
        , array(TRUE, 'https://example.com')
        , array(TRUE, 'http://*.example.com')
        , array(FALSE, 'exam*ple.com')
        , array(TRUE, '127.0.255.1')
        , array(TRUE, 'localhost')
        , array(FALSE, 'www.example.*')
        , array(FALSE, 'www.exa*le.com')
        , array(FALSE, 'www.example.*com')
        , array(FALSE, '*.example.*')
        , array(FALSE, 'gasldf*$#a0sdf0a8sdf')
        );
    }

    /**
     * @dataProvider ports
     */
    public function testPortValidation($accept, $ports) {
        $this->assertEquals($accept, $this->_policy->validatePorts($ports));
    }

    public static function ports() {
        return array(
            array(TRUE, '*')
        , array(TRUE, '80')
        , array(TRUE, '80,443')
        , array(TRUE, '507,516-523')
        , array(TRUE, '507,516-523,333')
        , array(TRUE, '507,516-523,507,516-523')
        , array(FALSE, '516-')
        , array(TRUE, '516-523,11')
        , array(FALSE, '516,-523,11')
        , array(FALSE, 'example')
        , array(FALSE, 'asdf,123')
        , array(FALSE, '--')
        , array(FALSE, ',,,')
        , array(FALSE, '838*')
        );
    }

    public function testAddAllowedAccessOnlyAcceptsValidPorts() {
        $this->setExpectedException('UnexpectedValueException');

        $this->_policy->addAllowedAccess('*', 'nope');
    }

    public function testSetSiteControlThrowsException() {
        $this->setExpectedException('UnexpectedValueException');

        $this->_policy->setSiteControl('nope');
    }

    public function testErrorClosesConnection() {
        $conn = $this->getMock('\\Ratchet\\ConnectionInterface');
        $conn->expects($this->once())->method('close');

        $this->_policy->onError($conn, new \Exception);
    }

    public function testOnMessageSendsString() {
        $this->_policy->addAllowedAccess('*', '*');

        $conn = $this->getMock('\\Ratchet\\ConnectionInterface');
        $conn->expects($this->once())->method('send')->with($this->isType('string'));

        $this->_policy->onMessage($conn, ' ');
    }

    public function testOnOpenExists() {
        $this->assertTrue(method_exists($this->_policy, 'onOpen'));
        $conn = $this->getMock('\Ratchet\ConnectionInterface');
        $this->_policy->onOpen($conn);
    }

    public function testOnCloseExists() {
        $this->assertTrue(method_exists($this->_policy, 'onClose'));
        $conn = $this->getMock('\Ratchet\ConnectionInterface');
        $this->_policy->onClose($conn);
    }
}
