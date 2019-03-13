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

namespace PainelDLX\Application\UseCases\GruposUsuarios\ConfigurarPermissoes;


use Doctrine\Common\Collections\ArrayCollection;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;

class ConfigurarPermissoesCommand
{
    /**
     * @var GrupoUsuario
     */
    private $grupo_usuario;
    /**
     * @var ArrayCollection
     */
    private $permissoes;

    /**
     * @return GrupoUsuario
     */
    public function getGrupoUsuario(): GrupoUsuario
    {
        return $this->grupo_usuario;
    }

    /**
     * @return ArrayCollection
     */
    public function getPermissoes(): ArrayCollection
    {
        return $this->permissoes;
    }

    /**
     * ConfigurarPermissoesCommand constructor.
     * @param GrupoUsuario $grupo_usuario
     * @param ArrayCollection $permissoes
     */
    public function __construct(GrupoUsuario $grupo_usuario, ArrayCollection $permissoes)
    {
        $this->grupo_usuario = $grupo_usuario;
        $this->permissoes = $permissoes;
    }
}