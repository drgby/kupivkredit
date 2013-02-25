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

use Kupivkredit\SignService\SignService;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-21 at 11:40:33.
 */
class SignServiceImplTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SignService
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SignService();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Kupivkredit\SignService\SignServiceImpl::sign
     */
    public function testSign()
    {
        $message = 'hello';
        $secret  = '123qwe';
        $sign = $this->object->sign($message, $secret);

        $this->assertInternalType('string', $sign);
        $this->assertEquals($sign, $this->object->sign($message, $secret));
        $this->assertNotEquals($sign, $this->object->sign($message, $secret.'1'));
    }
}
