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

use DLX\Infrastructure\EntityManagerX;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use PainelDLX\Presentation\Web\GruposUsuarios\Controllers\ConfigurarPermissoesController;
use PainelDLX\Tests\TestCase\PainelDLXTestCase;
use PainelDLX\Tests\TestCase\TesteComTransaction;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException;
use SechianeX\Exceptions\SessionAdapterNaoEncontradoException;
use SechianeX\Factories\SessionFactory;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class ConfigurarPermissoesControllerTest
 * @package PainelDLX\Testes\Presentation\Web\GruposUsuarios\Controllers
 * @coversDefaultClass \PainelDLX\Presentation\Web\GruposUsuarios\Controllers\ConfigurarPermissoesController
 */
class ConfigurarPermissoesControllerTest extends PainelDLXTestCase
{
    use TesteComTransaction;

    /**
     * @return ConfigurarPermissoesController
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     */
    public function test__construct(): ConfigurarPermissoesController
    {
        $session = SessionFactory::createPHPSession();
        $session->set('vilex:pagina-mestra', 'painel-dlx-master');

        $controller = self::$painel_dlx->getContainer()->get(ConfigurarPermissoesController::class);

        $this->assertInstanceOf(ConfigurarPermissoesController::class, $controller);

        return $controller;
    }

    /**
     * @return int
     * @throws ORMException
     * @throws DBALException
     */
    private function getIdGrupoUsuario(): int
    {
        $query = '
            select
                *
            from
                GrupoUsuario
            order by 
                rand()
            limit 1
        ';

        $sql = EntityManagerX::getInstance()->getConnection()->executeQuery($query);
        $grupo_usuario_id = $sql->fetchColumn();

        if (empty($grupo_usuario_id)) {
            $this->markTestIncomplete('Nenhum grupo de usuário encontrado para executar o teste.');
        }

        return $grupo_usuario_id;
    }

    /**
     * @param ConfigurarPermissoesController $controller
     * @throws ContextoInvalidoException
     * @throws DBALException
     * @throws ORMException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     * @covers ::formConfigurarPermissao
     * @depends test__construct
     */
    public function test_FormConfigurarPermissao_deve_retornar_um_HtmlResponse(ConfigurarPermissoesController $controller)
    {
        $grupo_usuario_id = $this->getIdGrupoUsuario();

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn(['grupo_usuario_id' => $grupo_usuario_id]);

        /** @var ServerRequestInterface $request */
        $response = $controller->formConfigurarPermissao($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @param ConfigurarPermissoesController $controller
     * @throws DBALException
     * @throws ORMException
     * @covers ::salvarConfiguracaoPermissao
     * @depends test__construct
     */
    public function test_SalvarConfiguracaoPermissao_deve_retornar_um_JsonResponse_sucesso(ConfigurarPermissoesController $controller)
    {
        $grupo_usuario_id = $this->getIdGrupoUsuario();

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([
            'grupo_usuario_id' => $grupo_usuario_id,
            'permissao_usuario_ids' => range(1, 100)
        ]);

        /** @var ServerRequestInterface $request */
        $response = $controller->salvarConfiguracaoPermissao($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());
        $this->assertEquals('sucesso', $json->retorno);
    }
}
