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

namespace PainelDLX\Tests\Presentation\Web\Emails\Controllers;

use DLX\Core\Configure;
use DLX\Infrastructure\EntityManagerX;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\ORMException;
use PainelDLX\Application\Factories\CommandBusFactory;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Presentation\Web\Emails\Controllers\ConfigSmtpController;
use PainelDLX\Tests\TestCase\PainelDLXTestCase;
use PainelDLX\Tests\TestCase\TesteComTransaction;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\TemplateInvalidoException;
use Vilex\VileX;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;

/**
 * Class ConfigSmtpControllerTestCase
 * @package PainelDLX\Testes\Presentation\Web\Emails\Controllers
 * @coversDefaultClass \PainelDLX\Presentation\Web\Emails\Controllers\ConfigSmtpController
 */
class ConfigSmtpControllerTest extends PainelDLXTestCase
{
    use TesteComTransaction;

    /**
     * @var ConfigSmtpController
     */
    private $controller;

    /**
     * @throws ORMException
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var SessionInterface $session */
        $this->controller = self::$painel_dlx->getContainer()->get(ConfigSmtpController::class);
    }

    /**
     * @return int
     * @throws ORMException
     * @throws Exception
     */
    public function inserirNovaConfiguracao(): int
    {
        $conn = EntityManagerX::getInstance()->getConnection();

        $query = 'insert into ConfiguracaoSmtp (servidor, porta, nome) values (:servidor, :porta, :nome)';
        $conn->executeQuery($query, [
            'servidor' => 'localhost',
            'porta' => 587,
            'nome' => 'Teste'
        ]);

        return $conn->lastInsertId();
    }

    /**
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     * @covers ::listaConfigSmtp
     */
    public function test_ListaConfigSmtp_deve_retornar_um_HtmlResponse()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn([
            'campos' => null,
            'busca' => null,
            'qtde' => null,
            'offset' => null,
            'pg' => null
        ]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->listaConfigSmtp($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @covers ::excluirConfigSmtp
     * @throws Exception
     * @throws ORMException
     * @covers ::excluirConfigSmtp
     */
    public function test_ExcluirConfigSmtp_deve_retornar_JsonResponse()
    {
        $id = $this->inserirNovaConfiguracao();

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([
            'config_smtp_id' => $id
        ]);

        /** @var ServerRequestInterface $request */

        $response = $this->controller->excluirConfigSmtp($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());
        $this->assertEquals('sucesso', $json->retorno);
    }

    /**
     * @throws Exception
     * @throws ORMException
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     * @covers ::detalheConfigSmtp
     */
    public function test_DetalheConfigSmtp_deve_retornar_um_HtmlResponse()
    {
        $id = $this->inserirNovaConfiguracao();

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn([
            'config_smtp_id' => $id
        ]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->detalheConfigSmtp($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @covers ::testarConfigSmtp
     * @throws TemplateInvalidoException
     */
    public function test_testarConfigSmtp_deve_retornar_um_JsonResponse_sucesso()
    {
        $server_request = $this->createMock(ServerRequestInterface::class);
        $server_request->method('getParsedBody')->willReturn([
            'servidor' => 'smtp.gmail.com',
            'porta' => 587,
            'requer_autent' => true,
            'conta' => 'dlepera88.emails@gmail.com',
            'senha' => 'oxswveitoainkmbu',
            'cripto' => 'tls',
            'de_nome' => null,
            'responder_para' => null,
            'corpo_html' => true
        ]);

        $command_bus = CommandBusFactory::create(
            self::$painel_dlx->getContainer(),
            Configure::get('app', 'mapping')
        );

        $session = $this->createMock(SessionInterface::class);
        $session->method('get')->willReturn(new Usuario('Teste', 'dlepera88@gmail.com'));

        /** @var ServerRequestInterface $server_request */
        /** @var SessionInterface $session */

        $controller = new ConfigSmtpController(
            new VileX(),
            $command_bus(),
            $session
        );

        $response = $controller->testarConfigSmtp($server_request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());

        $this->assertEquals('sucesso', $json->retorno);
    }
}
