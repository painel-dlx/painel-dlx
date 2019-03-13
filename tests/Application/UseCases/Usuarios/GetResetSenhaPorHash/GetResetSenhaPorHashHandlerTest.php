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

namespace PainelDLX\Testes\Application\UseCases\Usuarios\GetResetSenhaPorHash;

use DLX\Infra\EntityManagerX;
use PainelDLX\Application\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommandHandler;
use PainelDLX\Application\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashCommand;
use PainelDLX\Application\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashCommandHandler;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Repositories\ResetSenhaRepositoryInterface;
use PainelDLX\Testes\Application\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaHandlerTest;
use PainelDLX\Testes\PainelDLXTests;

class GetResetSenhaPorHashHandlerTest extends PainelDLXTests
{

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontrado
     */
    public function test_Handle()
    {
        /** @var ResetSenhaRepositoryInterface $reset_senha_repository */
        $reset_senha_repository = EntityManagerX::getRepository(ResetSenha::class);

        $reset_senha = (new SolicitarResetSenhaHandlerTest())->test_Handle();

        $command = new GetResetSenhaPorHashCommand($reset_senha->getHash());
        $reset_senha2 = (new GetResetSenhaPorHashCommandHandler($reset_senha_repository))->handle($command);

        $this->assertNotNull($reset_senha2);
        $this->assertEquals($reset_senha, $reset_senha2);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function test_Handle_deve_retornar_null_quando_nao_encontrar()
    {
        /** @var ResetSenhaRepositoryInterface $reset_senha_repository */
        $reset_senha_repository = EntityManagerX::getRepository(ResetSenha::class);

        $command = new GetResetSenhaPorHashCommand('teste');
        $reset_senha = (new GetResetSenhaPorHashCommandHandler($reset_senha_repository))->handle($command);

        $this->assertNull($reset_senha);
    }
}
