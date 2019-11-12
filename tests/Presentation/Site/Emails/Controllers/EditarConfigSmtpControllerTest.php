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

namespace PainelDLX\Testes\Presentation\Site\Emails\Controllers;

use DLX\Infrastructure\EntityManagerX;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use PainelDLX\Presentation\Site\Emails\Controllers\EditarConfigSmtpController;
use PainelDLX\Tests\TestCase\PainelDLXTestCase;
use PainelDLX\Tests\TestCase\TesteComTransaction;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class EditarConfigSmtpControllerTest
 * @package PainelDLX\Testes\Presentation\Site\Emails\Controllers
 * @coversDefaultClass \PainelDLX\Presentation\Site\Emails\Controllers\EditarConfigSmtpController
 */
class EditarConfigSmtpControllerTest extends PainelDLXTestCase
{
    use TesteComTransaction;

    /**
     * @var EditarConfigSmtpController
     */
    private $controller;

    /**
     * @throws ORMException
     */
    protected function setUp()
    {
        parent::setUp();
        $this->controller = self::$painel_dlx->getContainer()->get(EditarConfigSmtpController::class);
    }

    /**
     * @return int
     * @throws DBALException
     * @throws ORMException
     */
    private function getIdConfigSmtp(): int
    {
        $query = '
            select
                config_smtp_id
            from
                ConfiguracaoSmtp
            order by
                rand()
            limit 1
        ';

        $sql = EntityManagerX::getInstance()->getConnection()->executeQuery($query);
        $id = $sql->fetchColumn();

        if (empty($id)) {
            $this->markTestIncomplete('Nenhuma configuração SMTP encontrada para fazer o teste.');
        }

        return $id;
    }

    /**
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     * @throws ORMException
     * @throws DBALException
     */
    public function test_FormEditarConfigSmtp_deve_retornar_HtmlResponse()
    {
        $config_smtp_id = $this->getIdConfigSmtp();

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn([
            'config_smtp_id' => $config_smtp_id
        ]);

        /** @var ServerRequestInterface $request */

        $response = $this->controller->formEditarConfigSmtp($request);
        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @throws DBALException
     * @throws ORMException
     */
    public function test_EditarConfigSmtp_deve_retornar_JsonResponse()
    {
        $config_smtp_id = $this->getIdConfigSmtp();

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([
            'config_smtp_id' => $config_smtp_id,
            'nome' => 'Teste de edição',
            'servidor' => 'localhost',
            'porta' => 25,
            'cripto' => null,
            'requer_autent' => false,
            'conta' => null,
            'senha' => null,
            'de_nome' => 'Painel DLX',
            'responder_para' => null,
            'corpo_html' => true
        ]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->editarConfigSmtp($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());

        $this->assertEquals('sucesso', $json->retorno);
        $this->assertNotNull($json->config_smtp_id);
    }
}
