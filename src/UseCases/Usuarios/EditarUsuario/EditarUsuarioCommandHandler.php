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

namespace PainelDLX\UseCases\Usuarios\EditarUsuario;

use PainelDLX\Domain\Usuarios\Exceptions\UsuarioInvalidoException;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioJaPossuiGrupoException;
use PainelDLX\Domain\Usuarios\Validators\SalvarUsuarioValidator;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;

/**
 * Class EditarUsuarioCommandHandler
 * @package PainelDLX\UseCases\Usuarios\EditarUsuario
 * @covers EditarUsuarioCommandHandlerTest
 */
class EditarUsuarioCommandHandler
{
    /**
     * @var UsuarioRepositoryInterface
     */
    private $usuario_repository;
    /**
     * @var GrupoUsuarioRepositoryInterface
     */
    private $grupo_usuario_repository;
    /**
     * @var SalvarUsuarioValidator
     */
    private $validator;

    /**
     * NovoUsuarioCommandHandler constructor.
     * @param UsuarioRepositoryInterface $usuario_repository
     * @param GrupoUsuarioRepositoryInterface $grupo_usuario_repository
     * @param SalvarUsuarioValidator $validator
     */
    public function __construct(
        UsuarioRepositoryInterface $usuario_repository,
        GrupoUsuarioRepositoryInterface $grupo_usuario_repository,
        SalvarUsuarioValidator $validator
    ) {
        $this->usuario_repository = $usuario_repository;
        $this->grupo_usuario_repository = $grupo_usuario_repository;
        $this->validator = $validator;
    }

    /**
     * @param EditarUsuarioCommand $command
     * @return Usuario
     * @throws UsuarioInvalidoException
     * @throws UsuarioJaPossuiGrupoException
     */
    public function handle(EditarUsuarioCommand $command)
    {
        $lista_grupos = $this->grupo_usuario_repository->getListaGruposByIds(...$command->getGrupos());

        $usuario = $command->getUsuario();
        $usuario
            ->setNome($command->getNome())
            ->setEmail($command->getEmail());

        $usuario->getGrupos()->clear();

        /** @var GrupoUsuario $grupo_usuario */
        foreach ($lista_grupos as $grupo_usuario) {
            $usuario->addGrupo($grupo_usuario);
        }

        // Verifica se o email desse usuário não está sendo usado por outro usuário
        $this->validator->validar($usuario, null);
        $this->usuario_repository->update($usuario);

        return $usuario;
    }
}