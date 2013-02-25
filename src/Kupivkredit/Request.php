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

namespace Kupivkredit;

use \SimpleXMLElement;

/**
 * Конверт API-запроса.
 * Расширяет класс SimpleXMLElement.
 *
 * @see \SimpleXMLElement
 *
 * @package Kupivkredit
 * @author Sergey Kamardin <s.kamardin@tcsbank.ru>
 */
class Request extends SimpleXMLElement
{
	const TAG = 'request';

	/**
	 * Представляет объект в виде строки.
	 *
	 * @return string
	 */
	public function toString()
	{
		return base64_encode($this->asXML());
	}
}
