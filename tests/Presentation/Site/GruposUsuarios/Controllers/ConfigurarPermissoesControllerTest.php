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

namespace PainelDLX\Testes\Presentation\Site\GruposUsuarios\Controllers;

use DLX\Core\CommandBus\CommandBusAdapter;
use DLX\Core\Configure;
use DLX\Infra\EntityManagerX;
use DLX\Infra\ORM\Doctrine\Services\TransacaoDoctrine;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use PainelDLX\Presentation\Site\GruposUsuarios\Controllers\ConfigurarPermissoesController;
use PainelDLX\Testes\Application\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioHandlerTest;
use PainelDLX\Testes\PainelDLXTests;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Factories\SessionFactory;
use Vilex\VileX;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class ConfigurarPermissoesControllerTest extends PainelDLXTests
{
    /** @var ConfigurarPermissoesController */
    private $controller;

    /**
     * @throws \DLX\Core\Exceptions\ArquivoConfiguracaoNaoEncontradoException
     * @throws \DLX\Core\Exceptions\ArquivoConfiguracaoNaoInformadoException
     * @throws \Doctrine\ORM\ORMException
     * @throws \PainelDLX\Application\Services\Exceptions\AmbienteNaoInformadoException
     * @throws \SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException
     * @throws \SechianeX\Exceptions\SessionAdapterNaoEncontradoException
     */
    protected function setUp()
    {
        parent::setUp();

        $session = SessionFactory::createPHPSession();
        $session->set('vilex:pagina-mestra', 'painel-dlx-master');

        $this->controller = new ConfigurarPermissoesController(
            new VileX(),
            CommandBusAdapter::create(new CommandHandlerMiddleware(
                new ClassNameExtractor,
                new ContainerLocator($this->container, Configure::get('app', 'mapping')),
                new HandleInflector
            )),
            new TransacaoDoctrine(EntityManagerX::getInstance()),
            $session
        );
    }

    /**
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     * @throws \Doctrine\ORM\ORMException
     */
    public function test_FormConfigurarPermissao_deve_retornar_um_HtmlResponse()
    {
        $grupo_usuario = (new NovoGrupoUsuarioHandlerTest())->test_Handle();

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getQueryParams')
            ->willReturn(['grupo_usuario_id' => $grupo_usuario->getGrupoUsuarioId()]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->formConfigurarPermissao($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function test_SalvarConfiguracaoPermissao_deve_retornar_um_JsonResponse_sucesso()
    {
        $grupo_usuario = (new NovoGrupoUsuarioHandlerTest())->test_Handle();

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getParsedBody')
            ->willReturn([
                'grupo_usuario_id' => $grupo_usuario->getGrupoUsuarioId(),
                'permissao_usuario_ids' => range(1, 100)
            ]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->salvarConfiguracaoPermissao($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());

        $this->assertEquals('sucesso', $json->retorno);
    }
}
