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

use PainelDLX\Domain\Usuarios\Exceptions\UsuarioInvalidoException;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioJaPossuiGrupoException;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\Validators\SalvarUsuarioValidator;
use PainelDLX\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommand;
use PainelDLX\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommandHandler;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PHPUnit\Framework\TestCase;

/**
 * Class NovoUsuarioCommandHandlerTest
 * @package PainelDLX\Testes\Application\UseCases\Usuarios\NovoUsuario
 * @coversDefaultClass NovoUsuarioCommandHandler
 */
class NovoUsuarioCommandHandlerTest extends TestCase
{
    /**
     * @return void
     * @throws UsuarioInvalidoException
     * @throws UsuarioJaPossuiGrupoException
     * @covers ::handle
     */
    public function test_Handle_deve_salvar_novo_Usuario()
    {
        $usuario_repository = $this->createMock(UsuarioRepositoryInterface::class);

        $validator = $this->createMock(SalvarUsuarioValidator::class);
        $validator->method('validar')->willReturn(true);

        /** @var UsuarioRepositoryInterface $usuario_repository */
        /** @var SalvarUsuarioValidator $validator */

        $nome = 'Teste Unitário';
        $email = 'teste@teste.com.br';
        $senha = '123456';
        $senha_confirm = '123456';
        $grupos = [];

        $command = new NovoUsuarioCommand($nome, $email, $senha, $senha_confirm, $grupos);
        $usuario = (new NovoUsuarioCommandHandler($usuario_repository, $validator))->handle($command);

        $this->assertInstanceOf(Usuario::class, $usuario);
        $this->assertEquals($nome, $usuario->getNome());
        $this->assertEquals($email, $usuario->getEmail());
        $this->assertEquals($senha, $usuario->getSenha());
    }
}
