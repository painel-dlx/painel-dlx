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

namespace PainelDLX\Testes\Application\UseCases\PermissoesUsuario\GetListaPermissaoUsuario;

use DLX\Infra\EntityManagerX;
use Doctrine\Common\Collections\ArrayCollection;
use PainelDLX\Application\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioHandler;
use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Repositories\PermissaoUsuarioRepositoryInterface;
use PainelDLX\Testes\PainelDLXTests;

class GetListaPermissaoUsuarioHandlerTest extends PainelDLXTests
{

    /** @var PermissaoUsuarioRepositoryInterface */
    private $permissao_usuario_repository;
    /** @var GetListaPermissaoUsuarioHandler */
    private $handler;

    protected function setUp()
    {
        parent::setUp();

        $this->permissao_usuario_repository = EntityManagerX::getRepository(PermissaoUsuario::class);
        $this->handler = new GetListaPermissaoUsuarioHandler($this->permissao_usuario_repository);
    }

    public function test_Handle_sem_criteria()
    {
        $command = new GetListaPermissaoUsuarioCommand();
        $lista_grupos_command = $this->handler->handle($command);
        $lista_grupos_repository = $this->permissao_usuario_repository->findBy(['deletado' => false]);

        $this->assertEquals($lista_grupos_repository, $lista_grupos_command);

        $lista_usuarios_command_collection = new ArrayCollection($lista_grupos_command);

        // Não pode trazer registros que estão marcados como deletado
        $this->assertFalse($lista_usuarios_command_collection->exists(function ($key, PermissaoUsuario $permissao_usuario) {
            return $permissao_usuario->isDeletado();
        }));
    }

    public function test_Handle_com_criteria()
    {
        $criteria = ['alias' => 'admin'];

        $command = new GetListaPermissaoUsuarioCommand($criteria);
        $lista_permissoes_command = $this->handler->handle($command);
        $lista_permissoes_repository = array_filter(
            $this->permissao_usuario_repository->findByLike($criteria),
            function (PermissaoUsuario $permissao_usuario) {
                return !$permissao_usuario->isDeletado();
            }
        );

        $this->assertEquals($lista_permissoes_repository, $lista_permissoes_command);

        $lista_permissoes_command_collection = new ArrayCollection($lista_permissoes_command);

        // Não pode trazer registros que estão marcados como deletado
        $this->assertFalse($lista_permissoes_command_collection->exists(function ($key, PermissaoUsuario $permissao_usuario) {
            return $permissao_usuario->isDeletado();
        }));
    }
}
