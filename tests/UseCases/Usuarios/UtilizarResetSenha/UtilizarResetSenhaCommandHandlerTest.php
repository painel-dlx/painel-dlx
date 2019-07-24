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

namespace PainelDLX\Testes\Application\UseCases\Usuarios\UtilizarResetSenha;

use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioJaPossuiGrupoException;
use PainelDLX\UseCases\Usuarios\UtilizarResetSenha\UtilizarResetSenhaCommand;
use PainelDLX\UseCases\Usuarios\UtilizarResetSenha\UtilizarResetSenhaCommandHandler;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Repositories\ResetSenhaRepositoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class UtilizarResetSenhaCommandHandlerTest
 * @package PainelDLX\Testes\Application\UseCases\Usuarios\UtilizarResetSenha
 * @coversDefaultClass \PainelDLX\UseCases\Usuarios\UtilizarResetSenha\UtilizarResetSenhaCommandHandler
 */
class UtilizarResetSenhaCommandHandlerTest extends TestCase
{
    /**
     * @throws UsuarioJaPossuiGrupoException
     * @covers ::handle
     */
    public function test_Handle_deve_utilizar_um_determinado_hash_de_ResetSenha_para_que_nao_seja_mais_utilizado()
    {
        $hash = uniqid();

        // Criar um usuário para poder pedir o reset de senha
        $usuario = new Usuario('Diego Lepera', 'dlepera88.emails@gmail.com');
        $usuario->setSenha('345nesf87p1AS');

        $reset_senha = new ResetSenha();
        $reset_senha->setUsuario($usuario);
        $reset_senha->setHash($hash);

        $reset_senha_repository = $this->createMock(ResetSenhaRepositoryInterface::class);

        /** @var ResetSenhaRepositoryInterface $reset_senha_repository */

        $command = new UtilizarResetSenhaCommand($reset_senha);
        $reset_senha = (new UtilizarResetSenhaCommandHandler($reset_senha_repository))->handle($command);

        $this->assertTrue($reset_senha->isUtilizado());
    }
}
