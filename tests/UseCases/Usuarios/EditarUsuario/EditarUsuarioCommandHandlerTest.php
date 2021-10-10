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

namespace PainelDLX\Tests\UseCases\Usuarios\EditarUsuario;

use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioInvalidoException;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioJaPossuiGrupoException;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\Validators\SalvarUsuarioValidator;
use PainelDLX\UseCases\Usuarios\EditarUsuario\EditarUsuarioCommand;
use PainelDLX\UseCases\Usuarios\EditarUsuario\EditarUsuarioCommandHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class EditarUsuarioCommandHandlerTest
 * @package PainelDLX\Tests\UseCases\Usuarios\EditarUsuario
 * @coversDefaultClass \PainelDLX\UseCases\Usuarios\EditarUsuario\EditarUsuarioCommandHandler
 */
class EditarUsuarioCommandHandlerTest extends TestCase
{
    /**
     * @covers ::handle
     * @throws UsuarioJaPossuiGrupoException
     * @throws UsuarioInvalidoException
     */
    public function test_Handle_deve_atualizar_informacoes_do_Usuario_e_salvar_no_banco()
    {
        $grupo_usuario = $this->createMock(GrupoUsuario::class);
        $grupo_usuario->method('getNome')->willReturn('Teste');
        $grupo_usuario->method('getAlias')->willReturn('TESTE');

        $usuario_repository = $this->createMock(UsuarioRepositoryInterface::class);

        $grupo_usuario_repository = $this->createMock(GrupoUsuarioRepositoryInterface::class);
        $grupo_usuario_repository->method('getListaGruposByIds')->willReturn([$grupo_usuario]);

        $validator = $this->createMock(SalvarUsuarioValidator::class);
        $validator->method('validar')->willReturn(true);

        /** @var GrupoUsuario $grupo_usuario */
        /** @var UsuarioRepositoryInterface $usuario_repository */
        /** @var GrupoUsuarioRepositoryInterface $grupo_usuario_repository */
        /** @var SalvarUsuarioValidator $validator */

        $usuario = new Usuario('Teste de Usuário', 'teste@gmail.com', $grupo_usuario);

        $nome = 'Nome do Usuário';
        $email = 'outro.email@gmail.com';

        $command = new EditarUsuarioCommand($usuario, $nome, $email, []);
        $usuario_alterado = (new EditarUsuarioCommandHandler(
            $usuario_repository,
            $grupo_usuario_repository,
            $validator
        ))->handle($command);

        $this->assertInstanceOf(Usuario::class, $usuario_alterado);
        $this->assertEquals($nome, $usuario_alterado->getNome());
        $this->assertEquals($email, $usuario_alterado->getEmail());
    }
}
