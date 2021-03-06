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

namespace PainelDLX\UseCases\PermissoesUsuario\EditarPermissaoUsuario;


use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Repositories\PermissaoUsuarioRepositoryInterface;
use PainelDLX\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioCommand;

class EditarPermissaoUsuarioCommandHandler
{
    /**
     * @var PermissaoUsuarioRepositoryInterface
     */
    private $permissao_usuario_repository;

    /**
     * EditarPermissaoUsuarioCommandHandler constructor.
     * @param PermissaoUsuarioRepositoryInterface $permissao_usuario_repository
     */
    public function __construct(PermissaoUsuarioRepositoryInterface $permissao_usuario_repository)
    {
        $this->permissao_usuario_repository = $permissao_usuario_repository;
    }

    /**
     * @param EditarPermissaoUsuarioCommand $command
     * @return PermissaoUsuario
     */
    public function handle(EditarPermissaoUsuarioCommand $command): PermissaoUsuario
    {
        /** @var PermissaoUsuario $permissao_usuario */
        $permissao_usuario = $this->permissao_usuario_repository->find($command->getPermissaoUsuarioId());
        $permissao_usuario->setDescricao($command->getDescricao());

        $this->permissao_usuario_repository->update($permissao_usuario);
        return $permissao_usuario;
    }
}