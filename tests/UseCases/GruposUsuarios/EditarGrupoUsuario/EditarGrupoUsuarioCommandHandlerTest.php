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

namespace PainelDLX\Tests\UseCases\GruposUsuarios\EditarGrupoUsuario;

use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\GruposUsuarios\Exceptions\GrupoUsuarioInvalidoException;
use PainelDLX\Domain\GruposUsuarios\Exceptions\GrupoUsuarioNaoEncontradoException;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\GruposUsuarios\Validators\AliasUtilizadoValidator;
use PainelDLX\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioCommand;
use PainelDLX\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioCommandHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class EditarGrupoUsuarioCommandHandlerTest
 * @package PainelDLX\Tests\UseCases\GruposUsuarios\EditarGrupoUsuario
 * @coversDefaultClass \PainelDLX\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioCommandHandler
 */
class EditarGrupoUsuarioCommandHandlerTest extends TestCase
{
    /**
     * @throws GrupoUsuarioNaoEncontradoException
     * @throws GrupoUsuarioInvalidoException
     * @covers ::handle
     */
    public function test_Handle_deve_lancar_excecao_quando_nao_encontrar_GrupoUsuario()
    {
        $grupo_usuario_id = mt_rand();
        $nome = 'Grupo de Teste';

        $grupo_usuario_repository = $this->createMock(GrupoUsuarioRepositoryInterface::class);
        $grupo_usuario_repository->method('find')->willReturn(null);

        $validator = $this->createMock(AliasUtilizadoValidator::class);

        /** @var GrupoUsuarioRepositoryInterface $grupo_usuario_repository */
        /** @var AliasUtilizadoValidator $validator */

        $this->expectException(GrupoUsuarioNaoEncontradoException::class);
        $this->expectExceptionCode(10);

        $command = new EditarGrupoUsuarioCommand($grupo_usuario_id, $nome);
        (new EditarGrupoUsuarioCommandHandler($grupo_usuario_repository, $validator))->handle($command);
    }

    /**
     * @throws GrupoUsuarioInvalidoException
     * @throws GrupoUsuarioNaoEncontradoException
     * @covers ::handle
     */
    public function test_Handle_deve_lancar_excecao_quando_alias_nao_for_valido()
    {
        $grupo_usuario_id = mt_rand();
        $nome = 'Grupo de Teste';
        $alias = 'GRUPO_DE_TESTE';

        $grupo_usuario = $this->createMock(GrupoUsuario::class);
        $grupo_usuario->method('getId')->willReturn($grupo_usuario_id);
        $grupo_usuario->method('getNome')->willReturn($nome);
        $grupo_usuario->method('getAlias')->willReturn('GRUPO_DE_TESTE');

        $grupo_usuario_repository = $this->createMock(GrupoUsuarioRepositoryInterface::class);
        $grupo_usuario_repository->method('find')->willReturn($grupo_usuario);

        $validator = $this->createMock(AliasUtilizadoValidator::class);
        $validator->method('validar')->willThrowException(GrupoUsuarioInvalidoException::aliasUtilizadoOutroGrupo($alias));

        /** @var GrupoUsuarioRepositoryInterface $grupo_usuario_repository */
        /** @var AliasUtilizadoValidator $validator */

        $this->expectException(GrupoUsuarioInvalidoException::class);
        $this->expectExceptionCode(10);

        $command = new EditarGrupoUsuarioCommand($grupo_usuario_id, $nome);
        (new EditarGrupoUsuarioCommandHandler($grupo_usuario_repository, $validator))->handle($command);
    }
}
