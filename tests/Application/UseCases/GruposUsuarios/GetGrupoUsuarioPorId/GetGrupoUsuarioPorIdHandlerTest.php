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

namespace PainelDLX\Testes\Application\UseCases\GruposUsuarios\GetGrupoUsuarioPorId;

use DLX\Infra\EntityManagerX;
use Doctrine\ORM\ORMException;
use PainelDLX\Application\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommandHandler;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Testes\Application\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioHandlerTest;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;

class GetGrupoUsuarioPorIdHandlerTest extends PainelDLXTestCase
{
    /**
     * @throws ORMException
     */
    public function test_Handle()
    {
        /** @var GrupoUsuarioRepositoryInterface $grupo_usuario_repository */
        $grupo_usuario_repository = EntityManagerX::getRepository(GrupoUsuario::class);

        $grupo_usuario = (new NovoGrupoUsuarioHandlerTest())->test_Handle();

        $command = new GetGrupoUsuarioPorIdCommand($grupo_usuario->getGrupoUsuarioId());
        $grupo_usuario2 = (new GetGrupoUsuarioPorIdCommandHandler($grupo_usuario_repository))->handle($command);

        $this->assertEquals($grupo_usuario, $grupo_usuario2);
    }
}
