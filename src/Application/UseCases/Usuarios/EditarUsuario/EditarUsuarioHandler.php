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

namespace PainelDLX\Application\UseCases\Usuarios\EditarUsuario;

use PainelDLX\Application\UseCases\Usuarios\Exceptions\RegistroEntityNaoEncontradoException;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\Services\VerificaEmailJaCadastrado;

class EditarUsuarioHandler
{
    /** @var UsuarioRepositoryInterface */
    private $usuario_repository;
    /** @var GrupoUsuarioRepositoryInterface */
    private $grupo_usuario_repository;

    /**
     * NovoUsuarioHandler constructor.
     * @param UsuarioRepositoryInterface $usuario_repository
     * @param GrupoUsuarioRepositoryInterface $grupo_usuario_repository
     */
    public function __construct(
        UsuarioRepositoryInterface $usuario_repository,
        GrupoUsuarioRepositoryInterface $grupo_usuario_repository
    ) {
        $this->usuario_repository = $usuario_repository;
        $this->grupo_usuario_repository = $grupo_usuario_repository;
    }

    /**
     * @param EditarUsuarioCommand $command
     * @throws \Exception
     */
    public function handle(EditarUsuarioCommand $command)
    {
        try {
            $lista_grupos = $this->grupo_usuario_repository->getListaGruposByIds(...$command->getGrupos());
            /** @var Usuario $usuario */
            $usuario = $this->usuario_repository->find($command->getUsuarioId());

            if (is_null($usuario)) {
                throw new RegistroEntityNaoEncontradoException('Usuário');
            }

            $usuario
                ->setNome($command->getNome())
                ->setEmail($command->getEmail());

            /** @var GrupoUsuario $grupo_usuario */
            foreach ($lista_grupos as $grupo_usuario) {
                if (!$usuario->hasGrupoUsuario($grupo_usuario)) {
                    $usuario->addGrupo($grupo_usuario);
                }
            }

            // Verifica se o email desse usuário não está sendo usado por outro usuário
            (new VerificaEmailJaCadastrado($this->usuario_repository, $usuario))->executar();
            $this->usuario_repository->update($usuario);

            return $usuario;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}