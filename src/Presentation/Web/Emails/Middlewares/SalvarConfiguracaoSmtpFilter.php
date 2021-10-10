<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 PHP DLX
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PainelDLX\Presentation\Web\Emails\Middlewares;


use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

/**
 * Class EditarConfiguracaoSmtpFilter
 * @package PainelDLX\Presentation\Web\Emails\Middlewares
 * @covers EditarConfiguracaoSmtpFilterTest
 */
class SalvarConfiguracaoSmtpFilter implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $parsed_body = $request->getParsedBody();

        $post = filter_var_array($parsed_body, [
            'config_smtp_id' => FILTER_VALIDATE_INT,
            'nome' => FILTER_SANITIZE_STRING,
            'servidor' => FILTER_SANITIZE_STRING,
            'porta' => FILTER_VALIDATE_INT,
            'cripto' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => ['regexp' => '~^(tls|ssl|)$~']
            ],
            'requer_autent' => FILTER_VALIDATE_BOOLEAN,
            'conta' => FILTER_SANITIZE_STRING,
            'senha' => FILTER_SANITIZE_STRING,
            'de_nome' => FILTER_SANITIZE_STRING,
            'responder_para' => FILTER_VALIDATE_EMAIL,
            'corpo_html' => FILTER_VALIDATE_BOOLEAN
        ]);

        try {
            if (empty($post['porta'])) {
                throw new InvalidArgumentException('Informe a porta de conexão ao servidor SMTP.');
            }

            if ($post['porta'] < 1 || $post['porta'] > 65535) {
                throw new InvalidArgumentException('A porta de conexão informada é inválida.');
            }

            if ($post['cripto'] === false) {
                throw new InvalidArgumentException('O tipo de criptografia informado é inválido.');
            }

            if ($post['requer_autent']) {
                if (empty($post['conta']) || empty($post['senha'])) {
                    throw new InvalidArgumentException('Informe a conta e senha para autenticação no servidor SMTP.');
                }
            }

            if (!empty($parsed_body['responder_para']) && $post['responder_para'] === false) {
                throw new InvalidArgumentException('Informe um email válido no campo "Responder para".');
            }
        } catch (InvalidArgumentException $e) {
            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();

            return new JsonResponse($json);
        }

        return $handler->handle($request->withParsedBody($post));
    }
}