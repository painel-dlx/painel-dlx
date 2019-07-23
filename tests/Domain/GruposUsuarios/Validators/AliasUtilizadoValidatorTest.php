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

namespace PainelDLX\Tests\Domain\GruposUsuarios\Validators;

use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\GruposUsuarios\Exceptions\GrupoUsuarioInvalidoException;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\GruposUsuarios\Validators\AliasUtilizadoValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class AliasUtilizadoValidatorTest
 * @package PainelDLX\Tests\Domain\GruposUsuarios\Validators
 * @coversDefaultClass \PainelDLX\Domain\GruposUsuarios\Validators\AliasUtilizadoValidator
 */
class AliasUtilizadoValidatorTest extends TestCase
{
    /**
     * @covers ::validar
     * @throws GrupoUsuarioInvalidoException
     */
    public function test_Validar_deve_lancar_excecao_quando_alias_ja_for_utilizado_por_outro_grupo()
    {
        $grupo_usuario = $this->createMock(GrupoUsuario::class);
        $grupo_usuario->method('getAlias')->willReturn('TESTE');

        $grupo_usuario_repository = $this->createMock(GrupoUsuarioRepositoryInterface::class);
        $grupo_usuario_repository->method('existsOutroGrupoComMesmoAlias')->willReturn(true);

        /** @var GrupoUsuarioRepositoryInterface $grupo_usuario_repository */
        /** @var GrupoUsuario $grupo_usuario */

        $this->expectException(GrupoUsuarioInvalidoException::class);
        $this->expectExceptionCode(10);

        (new AliasUtilizadoValidator($grupo_usuario_repository))->validar($grupo_usuario);
    }

    /**
     * @throws GrupoUsuarioInvalidoException
     * @covers ::validar
     */
    public function test_Validar_deve_retornar_true_quando_alias_for_valido()
    {
        $grupo_usuario = $this->createMock(GrupoUsuario::class);
        $grupo_usuario->method('getAlias')->willReturn('TESTE');

        $grupo_usuario_repository = $this->createMock(GrupoUsuarioRepositoryInterface::class);
        $grupo_usuario_repository->method('existsOutroGrupoComMesmoAlias')->willReturn(false);

        /** @var GrupoUsuarioRepositoryInterface $grupo_usuario_repository */
        /** @var GrupoUsuario $grupo_usuario */

        $isValido = (new AliasUtilizadoValidator($grupo_usuario_repository))->validar($grupo_usuario);

        $this->assertTrue($isValido);
    }
}
