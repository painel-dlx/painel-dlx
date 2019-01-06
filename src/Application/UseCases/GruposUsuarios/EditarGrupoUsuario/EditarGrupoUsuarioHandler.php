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

namespace PainelDLX\Application\UseCases\GruposUsuarios\EditarGrupoUsuario;

use PainelDLX\Application\UseCases\CadastroUsuarios\Exceptions\RegistroEntityNaoEncontradoException;
use PainelDLX\Domain\CadastroUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\CadastroUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\CadastroUsuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Domain\CadastroUsuarios\Services\VerificaAliasGrupoUsuarioJaExiste;

class EditarGrupoUsuarioHandler
{
    /** @var GrupoUsuarioRepositoryInterface */
    private $grupo_usuario_repository;

    /**
     * NovoUsuarioHandler constructor.
     * @param UsuarioRepositoryInterface $usuario_repository
     * @param GrupoUsuarioRepositoryInterface $grupo_usuario_repository
     */
    public function __construct(
        GrupoUsuarioRepositoryInterface $grupo_usuario_repository
    ) {
        $this->grupo_usuario_repository = $grupo_usuario_repository;
    }

    /**
     * @param EditarGrupoUsuarioCommand $command
     * @throws \Exception
     */
    public function handle(EditarGrupoUsuarioCommand $command)
    {
        try {
            /** @var GrupoUsuario $grupo_usuario */
            $grupo_usuario = $this->grupo_usuario_repository->find($command->getGrupoUsuarioId());

            if (!$grupo_usuario instanceof GrupoUsuario) {
                throw new RegistroEntityNaoEncontradoException('Grupo de Usuário');
            }

            $grupo_usuario->setNome($command->getNome());

            // Verificar se o alias gerado não está sendo utilizado
            (new VerificaAliasGrupoUsuarioJaExiste($this->grupo_usuario_repository, $grupo_usuario));

            $this->grupo_usuario_repository->update($grupo_usuario);

            return $grupo_usuario;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}