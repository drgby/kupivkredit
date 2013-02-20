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

namespace Kupivkredit\EnvelopeBuilder;

use Kupivkredit\XMLBuilder\IXMLBuilder;
use Kupivkredit\Request;
use Kupivkredit\Envelope;
use Kupivkredit\SignService\ISignService;

/**
 * Имплементация билдера конверта API-вызова.
 *
 * @package EnvelopeBuilder
 * @author Sergey Kamardin <s.kamardin@tcsbank.ru>
 */
class EnvelopeBuilder implements IEnvelopeBuilder
{
    /**
     * Сервис подписи сообщения.
     *
     * @var ISignService
     */
    protected $signService = null;

	/**
	 * Создает конверт API-вызова.
	 *
	 * @param  Request               $request
	 * @param  string                $apiSecret
	 * @return \Kupivkredit\Envelope
	 */
	public function build(Request $request, $apiSecret)
    {
        $base64  = base64_encode($request->asXML());
        $sign    = $this->signService->sign($base64, $apiSecret);

        $envelope = new Envelope(sprintf('<%1$s></%1$s>', Envelope::TAG));
        $envelope->addChild(Envelope::MESSAGE, $base64);
        $envelope->addChild(Envelope::SIGN, $sign);

        return $envelope;
    }

    /**
     * Устанавливает сервис подписи сообщений.
     *
     * @param ISignService $signService
     */
    public function setSignService(ISignService $signService)
    {
        $this->signService = $signService;
    }
}