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

namespace PainelDLX\UseCases\GruposUsuarios\ConfigurarPermissoes;


use Doctrine\Common\Collections\ArrayCollection;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;

class ConfigurarPermissoesCommandHandler
{
    /**
     * @var GrupoUsuarioRepositoryInterface
     */
    private $grupo_usuario_repository;

    /**
     * ConfigurarPermissoesCommandHandler constructor.
     * @param GrupoUsuarioRepositoryInterface $grupo_usuario_repository
     */
    public function __construct(GrupoUsuarioRepositoryInterface $grupo_usuario_repository)
    {
        $this->grupo_usuario_repository = $grupo_usuario_repository;
    }

    /**
     * @param ConfigurarPermissoesCommand $command
     * @return GrupoUsuario
     */
    public function handle(ConfigurarPermissoesCommand $command): GrupoUsuario
    {
        $grupo_usuario = $command->getGrupoUsuario();
        $permissoes = $command->getPermissoes();

        $this->adicionarPermissoes($grupo_usuario, $permissoes);
        $this->excluirPermissoes($grupo_usuario, $permissoes);

        // Registrar as alterações no BD
        $this->grupo_usuario_repository->update($grupo_usuario);

        return $grupo_usuario;
    }

    /**
     * Adicionar as permissões ao grupo de usuários
     * @param GrupoUsuario $grupo_usuario
     * @param ArrayCollection $permissoes
     */
    private function adicionarPermissoes(GrupoUsuario $grupo_usuario, ArrayCollection $permissoes): void
    {
        $permissoes->map(function (PermissaoUsuario $permissao_usuario) use ($grupo_usuario) {
            if (!$grupo_usuario->hasPermissao($permissao_usuario)) {
                $grupo_usuario->addPermissao($permissao_usuario);
            }
        });
    }

    /**
     * @param GrupoUsuario $grupo_usuario
     * @param ArrayCollection $permissoes
     */
    private function excluirPermissoes(GrupoUsuario $grupo_usuario, ArrayCollection $permissoes): void
    {
        $grupo_usuario->getPermissoes()->map(function (PermissaoUsuario $permissao_usuario) use ($permissoes, $grupo_usuario) {
            if (!$permissoes->contains($permissao_usuario)) {
                $grupo_usuario->getPermissoes()->removeElement($permissao_usuario);
            }
        });
    }
}