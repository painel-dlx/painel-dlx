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

namespace PainelDLX\Tests\UseCases\Usuarios\AlterarSenhaUsuario;

use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioInvalidoException;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioJaPossuiGrupoException;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\Validators\ValidarSenhas;
use PainelDLX\Domain\Usuarios\ValueObjects\SenhaUsuario;
use PainelDLX\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommand;
use PainelDLX\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommandHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class AlterarSenhaUsuarioCommandHandlerTest
 * @package PainelDLX\Tests\UseCases\Usuarios\AlterarSenhaUsuario
 * @coversDefaultClass \PainelDLX\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommandHandler
 */
class AlterarSenhaUsuarioCommandHandlerTest extends TestCase
{
    /**
     * @covers ::handle
     * @throws UsuarioInvalidoException
     * @throws UsuarioJaPossuiGrupoException
     */
    public function test_Handle_deve_alterar_senha_do_usuario_e_salvar_no_bd()
    {
        $usuario_repository = $this->createMock(UsuarioRepositoryInterface::class);
        $usuario_repository->method('update')->willReturn(null);

        $validar_senhas = $this->createMock(ValidarSenhas::class);
        $validar_senhas->method('validar')->willReturn(true);

        /** @var UsuarioRepositoryInterface $usuario_repository */
        /** @var ValidarSenhas $validar_senhas */

        $nova_senha = 'outrasenha';

        $senha_usuario = new SenhaUsuario($nova_senha, $nova_senha, true);

        $usuario = new Usuario('Teste de Usuário', 'teste@gmail.com');
        $usuario->setSenha('teste');

        $command = new AlterarSenhaUsuarioCommand($usuario, $senha_usuario);
        $usuario_alterado = (new AlterarSenhaUsuarioCommandHandler($usuario_repository, $validar_senhas))->handle($command);

        $this->assertEquals($nova_senha, $usuario_alterado->getSenha());
    }
}
