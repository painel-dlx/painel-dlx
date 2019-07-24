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

namespace PainelDLX\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId;


use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Exceptions\PermissaoUsuarioNaoEncontradaException;
use PainelDLX\Domain\PermissoesUsuario\Repositories\PermissaoUsuarioRepositoryInterface;

/**
 * Class GetPermissaoUsuarioPorIdCommandHandler
 * @package PainelDLX\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId
 * @covers GetPermissaoUsuarioPorIdCommandHandlerTest
 */
class GetPermissaoUsuarioPorIdCommandHandler
{
    /**
     * @var PermissaoUsuarioRepositoryInterface
     */
    private $permissao_usuario_repository;

    /**
     * GetPermissaoUsuarioPorIdCommandHandler constructor.
     * @param PermissaoUsuarioRepositoryInterface $permissao_usuario_repository
     */
    public function __construct(PermissaoUsuarioRepositoryInterface $permissao_usuario_repository)
    {
        $this->permissao_usuario_repository = $permissao_usuario_repository;
    }

    /**
     * @param GetPermissaoUsuarioPorIdCommand $command
     * @return PermissaoUsuario|null
     * @throws PermissaoUsuarioNaoEncontradaException
     */
    public function handle(GetPermissaoUsuarioPorIdCommand $command): ?PermissaoUsuario
    {
        $permissao_usuario_id = $command->getId();

        $permissao_usuario = $this->permissao_usuario_repository->find($permissao_usuario_id);

        if (is_null($permissao_usuario)) {
            throw PermissaoUsuarioNaoEncontradaException::porId($permissao_usuario_id);
        }

        return $permissao_usuario;
    }
}