<?php
/**
 * Этот файл является частью библиотеки КупиВкредит.
 *
 * Все права защищены (c) 2012 «Тинькофф Кредитные Системы» Банк (закрытое акционерное общество)
 *
 * Информация о типе распространения данного ПО указана в файле LICENSE,
 * распространяемого вместе с исходным кодом библиотеки.
 *
 * This file is part of the KupiVkredit library.
 *
 * Copyright (c) 2012  «Tinkoff Credit Systems» Bank (closed joint-stock company)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Kupivkredit\DependencyManager\DependencyManager;
use Kupivkredit\DependencyManager\Exception\DependencyManagerException;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-21 at 11:40:34.
 *
 * @package DependencyManager
 * @author Sergey Kamardin <s.kamardin@tcsbank.ru>
 */
class DependencyManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DependencyManager
     */
    protected $object;

    public static $property;
    public static $argument;
    public static $call;
    public static $config;
    public static $service;

    /**
     * Инициализация конфига перед запуском всех тестов.
     */
    public static function setUpBeforeClass()
    {
        require_once 'TestService.php';

        self::$property = uniqid('property');
        self::$argument = uniqid('argument');
        self::$call     = uniqid('call');
        self::$service  = 'test';

        self::$config = array(
            'properties' => array(
                'property' => self::$property,
                'argument' => self::$argument,
                'call'     => self::$call,
            ),
            self::$service => array(
                'class' => 'TestService',
                'arguments' => array(
                    '%argument%'
                ),
                'calls' => array(
                    'setCall' => array('%call%'),
                ),
                'properties' => array(
                    'property' => '%property%',
                ),
            ),
        );
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new DependencyManager();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Kupivkredit\DependencyManager\DependencyManager::setConfig
     */
    public function testSetConfig()
    {
        $this->object->setConfig(self::$config);
    }

	/**
	 * @covers Kupivkredit\DependencyManager\DependencyManager::setConfig
	 * @expectedException Kupivkredit\DependencyManager\Exception\DependencyManagerException
	 */
	public function testSetConfigToAlreadyConfiguredObject()
	{
		$this->object->setConfig(array());
		$this->object->setConfig(array());
	}

    /**
     * @covers Kupivkredit\DependencyManager\DependencyManager::get
     * @covers Kupivkredit\DependencyManager\DependencyManager::constructService
     */
    public function testGetExistingService()
    {
        $this->object->setConfig(self::$config);

        /* @var $testService TestService */
        $testService = $this->object->get(self::$service);

        $this->assertInstanceOf('TestService', $testService);
        $this->assertEquals(self::$property, $testService->property);
        $this->assertEquals(self::$argument, $testService->getArgument());
        $this->assertEquals(self::$call, $testService->getCall());
    }

    /**
     * Пробует получить несуществующий сервис.
     *
     * @covers Kupivkredit\DependencyManager\DependencyManager::get
     * @expectedException Kupivkredit\DependencyManager\Exception\DependencyManagerException
     */
    public function testGetNotExistingService()
    {
        $this->object->setConfig(self::$config);
        $this->object->get(uniqid());
    }

    /**
     * @covers Kupivkredit\DependencyManager\DependencyManager::getProperty
     */
    public function testGetProperty()
    {
        $this->object->setConfig(self::$config);
        $property = $this->object->getProperty('property');

        $this->assertEquals(self::$property, $property);
    }

    /**
     * @covers Kupivkredit\DependencyManager\DependencyManager::getProperty
     * @expectedException Kupivkredit\DependencyManager\Exception\DependencyManagerException
     */
    public function testGetNotExistingProperty()
    {
        $this->object->setConfig(self::$config);
        $this->object->getProperty(uniqid());
    }

    /**
     * @covers Kupivkredit\DependencyManager\DependencyManager::parseConfig
     */
    public function testParseConfig()
    {
        // Глубина рекурсии
        $deep = 5;

        $this->object->setConfig(
            array(
                'properties' => array('property'=>self::$property),
                'stdclass'   => array('class' => '\stdClass'),
            )
        );

        $recursion = function (Closure $recursion, $deep, $count = 0) {
            $array = array(
                'property' => '%property%',
                'stdclass' => '@stdclass'
            );

            if ($count < $deep) {
                $count++;
                $array['recursion'] = call_user_func_array($recursion, array($recursion, $deep, $count));
            }

            return $array;
        };

        $config = call_user_func_array($recursion, array($recursion, $deep));

        $total    = 0;
        $phpunit  = $this;
        $property = self::$property;

        $check = function (Closure $check, array $path) use (&$total, $phpunit, $property) {
            $phpunit->assertEquals($property, $path['property']);
            $phpunit->assertInstanceOf('\stdClass', $path['stdclass']);

            if (isset($path['recursion'])) {
                $total++;
                call_user_func_array($check, array($check, $path['recursion']));
            }
        };

        $parsed = $this->object->parseConfig($config);
        call_user_func_array($check, array($check, $parsed));

        $this->assertEquals($deep, $total);
    }
}
