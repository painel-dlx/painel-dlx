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

namespace PainelDLX\Application\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario;



use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Repositories\PermissaoUsuarioRepositoryInterface;

class CadastrarPermissaoUsuarioHandler
{
    /**
     * @var PermissaoUsuarioRepositoryInterface
     */
    private $permissao_usuario_repository;

    /**
     * CadastrarPermissaoUsuarioHandler constructor.
     * @param PermissaoUsuario $permissao_usuario
     * @param PermissaoUsuarioRepositoryInterface $permissao_usuario_repository
     */
    public function __construct(
        PermissaoUsuarioRepositoryInterface $permissao_usuario_repository
    ) {
        $this->permissao_usuario_repository = $permissao_usuario_repository;
    }

    /**
     * @param CadastrarPermissaoUsuarioCommand $command
     */
    public function handle(CadastrarPermissaoUsuarioCommand $command): PermissaoUsuario
    {
        $permissao_usuario = PermissaoUsuario::create($command->getAlias(), $command->getDescricao());
        $this->permissao_usuario_repository->create($permissao_usuario);

        return $permissao_usuario;
    }
}