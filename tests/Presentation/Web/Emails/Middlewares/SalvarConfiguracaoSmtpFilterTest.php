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

namespace PainelDLX\Tests\Presentation\Web\Emails\Middlewares;

use PainelDLX\Presentation\Web\Emails\Middlewares\SalvarConfiguracaoSmtpFilter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class EditarConfiguracaoSmtpFilterTest
 * @package PainelDLX\Tests\Presentation\Web\Emails\Middlewares
 * @coversDefaultClass \PainelDLX\Presentation\Web\Emails\Middlewares\SalvarConfiguracaoSmtpFilter
 */
class SalvarConfiguracaoSmtpFilterTest extends TestCase
{
    /**
     * Porta HTTP inválidas
     * @return array
     */
    public function providerPortaInvalida(): array
    {
        return [
            [mt_rand(65536, 100000)],
            [mt_rand(-65535, 0)]
        ];
    }

    /**
     * Valores de porta HTTP que serão consideradas como "não informadas"
     * @return array
     */
    public function providerPortaNaoInformada(): array
    {
        return [
            [0],
            [null],
            ['']
        ];
    }

    /**
     * Conta e senha de autenticação inválidos
     * @return array
     */
    public function providerContaSenhaInvalidos(): array
    {
        return [
            [null, 'abcde'],
            ['teste@email.com', null],
            ['', 'abcde'],
            ['teste@email.com', '']
        ];
    }

    /**
     * Valores para testar o campo "Responder para"
     * @return array
     */
    public function providerResponderPara(): array
    {
        return [
            ['teste_email_invalido'],
            [''],
            [null]
        ];
    }

    /**
     * @param $porta
     * @covers ::process
     * @dataProvider providerPortaNaoInformada
     */
    public function test_Process_deve_retornar_JsonResponse_com_erro_quando_nao_informar_valor_porta($porta)
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('withParsedBody')->willReturn($request);
        $request->method('getParsedBody')->willReturn([
            'config_smtp_id' => 1,
            'nome' => 'Teste',
            'servidor' => 'smtp.teste.com.br',
            'porta' => $porta,
            'cripto' => 'tls',
            'requer_autent' => false,
            'conta' => null,
            'senha' => null,
            'de_nome' => null,
            'responder_para' => null,
            'corpo_email' => false
        ]);

        $response_generica = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response_generica);

        /** @var RequestHandlerInterface $handler */
        /** @var ServerRequestInterface $request */

        $response = (new SalvarConfiguracaoSmtpFilter())->process($request, $handler);
        $response_json = json_decode((string)$response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertObjectHasAttribute('retorno', $response_json);
        $this->assertObjectHasAttribute('mensagem', $response_json);
        $this->assertEquals('erro', $response_json->retorno);
        $this->assertEquals('Informe a porta de conexão ao servidor SMTP.', $response_json->mensagem);
    }

    /**
     * @covers ::process
     * @param int $porta
     * @dataProvider providerPortaInvalida
     */
    public function test_Process_deve_retornar_JsonResponse_com_erro_quando_informar_valor_porta_invalido(int $porta)
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('withParsedBody')->willReturn($request);
        $request->method('getParsedBody')->willReturn([
            'config_smtp_id' => 1,
            'nome' => 'Teste',
            'servidor' => 'smtp.teste.com.br',
            'porta' => $porta,
            'cripto' => 'tls',
            'requer_autent' => false,
            'conta' => null,
            'senha' => null,
            'de_nome' => null,
            'responder_para' => null,
            'corpo_email' => false
        ]);

        $response_generica = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response_generica);

        /** @var RequestHandlerInterface $handler */
        /** @var ServerRequestInterface $request */

        $response = (new SalvarConfiguracaoSmtpFilter())->process($request, $handler);
        $response_json = json_decode((string)$response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertObjectHasAttribute('retorno', $response_json);
        $this->assertObjectHasAttribute('mensagem', $response_json);
        $this->assertEquals('erro', $response_json->retorno);
        $this->assertEquals('A porta de conexão informada é inválida.', $response_json->mensagem);
    }

    /**
     * @covers ::process
     */
    public function test_Process_deve_retornar_JsonResponse_com_erro_quando_informar_valor_cripto_invalido()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('withParsedBody')->willReturn($request);
        $request->method('getParsedBody')->willReturn([
            'config_smtp_id' => 1,
            'nome' => 'Teste',
            'servidor' => 'smtp.teste.com.br',
            'porta' => 25,
            'cripto' => 'ttl',
            'requer_autent' => false,
            'conta' => null,
            'senha' => null,
            'de_nome' => null,
            'responder_para' => null,
            'corpo_email' => false
        ]);

        $response_generica = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response_generica);

        /** @var RequestHandlerInterface $handler */
        /** @var ServerRequestInterface $request */

        $response = (new SalvarConfiguracaoSmtpFilter())->process($request, $handler);
        $response_json = json_decode((string)$response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertObjectHasAttribute('retorno', $response_json);
        $this->assertObjectHasAttribute('mensagem', $response_json);
        $this->assertEquals('erro', $response_json->retorno);
        $this->assertEquals('O tipo de criptografia informado é inválido.', $response_json->mensagem);
    }

    /**
     * @param string|null $usuario
     * @param string|null $senha
     * @covers ::process
     * @dataProvider providerContaSenhaInvalidos
     */
    public function test_Process_deve_retornar_JsonResponse_com_erro_quando_requer_autent_e_conta_ou_senha_nao_informados(?string $usuario, ?string $senha)
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('withParsedBody')->willReturn($request);
        $request->method('getParsedBody')->willReturn([
            'config_smtp_id' => 1,
            'nome' => 'Teste',
            'servidor' => 'smtp.teste.com.br',
            'porta' => 25,
            'cripto' => 'ssl',
            'requer_autent' => true,
            'conta' => $usuario,
            'senha' => $senha,
            'de_nome' => null,
            'responder_para' => null,
            'corpo_email' => false
        ]);

        $response_generica = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response_generica);

        /** @var RequestHandlerInterface $handler */
        /** @var ServerRequestInterface $request */

        $response = (new SalvarConfiguracaoSmtpFilter())->process($request, $handler);
        $response_json = json_decode((string)$response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertObjectHasAttribute('retorno', $response_json);
        $this->assertObjectHasAttribute('mensagem', $response_json);
        $this->assertEquals('erro', $response_json->retorno);
        $this->assertEquals('Informe a conta e senha para autenticação no servidor SMTP.', $response_json->mensagem);
    }

    /**
     * @param string|null $responder_para
     * @covers ::process
     * @dataProvider providerResponderPara
     */
    public function test_Process_deve_retornar_JsonResponse_com_erro_quando_responder_invalido_mas_nao_quando_responder_para_nao_for_informado(?string $responder_para)
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('withParsedBody')->willReturn($request);
        $request->method('getParsedBody')->willReturn([
            'config_smtp_id' => 1,
            'nome' => 'Teste',
            'servidor' => 'smtp.teste.com.br',
            'porta' => 25,
            'cripto' => '',
            'requer_autent' => false,
            'conta' => null,
            'senha' => null,
            'de_nome' => null,
            'responder_para' => $responder_para,
            'corpo_email' => false
        ]);

        $response_generica = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response_generica);

        /** @var RequestHandlerInterface $handler */
        /** @var ServerRequestInterface $request */

        $response = (new SalvarConfiguracaoSmtpFilter())->process($request, $handler);

        if (!empty($responder_para)) {
            $response_json = json_decode((string)$response->getBody());

            $this->assertInstanceOf(JsonResponse::class, $response);
            $this->assertObjectHasAttribute('retorno', $response_json);
            $this->assertObjectHasAttribute('mensagem', $response_json);
            $this->assertEquals('erro', $response_json->retorno);
            $this->assertEquals('Informe um email válido no campo "Responder para".', $response_json->mensagem);
        } else {
            $this->assertSame($response, $response_generica);
        }
    }
}
