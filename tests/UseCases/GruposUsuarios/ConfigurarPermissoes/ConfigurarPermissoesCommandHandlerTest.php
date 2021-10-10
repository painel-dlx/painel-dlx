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

namespace PainelDLX\Testes\Application\UseCases\GruposUsuarios\ConfigurarPermissoes;

use DLX\Infrastructure\EntityManagerX;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\ORMException;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesCommand;
use PainelDLX\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesCommandHandler;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigurarPermissoesCommandHandlerTest
 * @package PainelDLX\Testes\Application\UseCases\GruposUsuarios\ConfigurarPermissoes
 * @coversDefaultClass \PainelDLX\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesCommandHandler
 */
class ConfigurarPermissoesCommandHandlerTest extends TestCase
{
    /**
     * @covers ::handle
     */
    public function test_Handle_deve_adicionar_permissoes_em_GrupoUsuario()
    {
        $grupo_usuario_repository = $this->createMock(GrupoUsuarioRepositoryInterface::class);

        /** @var GrupoUsuarioRepositoryInterface $grupo_usuario_repository */

        $permissoes = new ArrayCollection();

        $grupo_usuario = new GrupoUsuario();
        $grupo_usuario->setNome('Grupo de Teste');
        $grupo_usuario->setAlias('GRUPO_DE_TESTE');

        $permissoes->add(new PermissaoUsuario('TESTE_1', 'Teste 1'));
        $permissoes->add(new PermissaoUsuario('TESTE_2', 'Teste 2'));
        $permissoes->add(new PermissaoUsuario('TESTE_3', 'Teste 3'));

        $command = new ConfigurarPermissoesCommand($grupo_usuario, $permissoes);
        $grupo_usuario = (new ConfigurarPermissoesCommandHandler($grupo_usuario_repository))->handle($command);

        $this->assertCount($permissoes->count(), $grupo_usuario->getPermissoes());
    }
}
