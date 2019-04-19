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

namespace PainelDLX\Testes\Application\UseCases\Usuarios\NovoUsuario;

use DLX\Infra\EntityManagerX;
use Doctrine\ORM\ORMException;
use Exception;
use PainelDLX\Application\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommandHandler;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;
use PainelDLX\Testes\TestCase\TesteComTransaction;

/**
 * Class NovoUsuarioCommandHandlerTest
 * @package PainelDLX\Testes\Application\UseCases\Usuarios\NovoUsuario
 * @coversDefaultClass \PainelDLX\Application\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommandHandler
 */
class NovoUsuarioCommandHandlerTest extends PainelDLXTestCase
{
    use TesteComTransaction;

    /**
     * @return NovoUsuarioCommandHandler
     * @throws ORMException
     */
    public function test__construct(): NovoUsuarioCommandHandler
    {
        /** @var UsuarioRepositoryInterface $usuario_repository */
        $usuario_repository = EntityManagerX::getRepository(Usuario::class);
        /** @var GrupoUsuarioRepositoryInterface $grupo_usuario_repository */
        $grupo_usuario_repository = EntityManagerX::getRepository(GrupoUsuario::class);

        $handler = new NovoUsuarioCommandHandler($usuario_repository, $grupo_usuario_repository);

        $this->assertInstanceOf(NovoUsuarioCommandHandler::class, $handler);

        return $handler;
    }

    /**
     * @return Usuario
     * @throws Exception
     * @covers ::handle
     * @depends test__construct
     */
    public function test_Handle_deve_salvar_novo_Usuario(NovoUsuarioCommandHandler $handler): Usuario
    {
        /** @var GrupoUsuarioRepositoryInterface $grupo_usuario_repository */
        $grupo_usuario_repository = EntityManagerX::getRepository(GrupoUsuario::class);
        /** @var GrupoUsuario $grupo_usuario */
        $grupo_usuario = $grupo_usuario_repository->findOneBy(['alias' => 'ADMIN']);

        $senha = '123456';
        $usuario = new Usuario('Teste Unitário', 'teste@teste.com.br');
        $usuario->setSenha($senha);
        $usuario->addGrupo($grupo_usuario);

        $command = new NovoUsuarioCommand($usuario, $senha);
        $handler->handle($command);

        $this->assertNotNull($usuario->getUsuarioId());

        return $usuario;
    }
}
