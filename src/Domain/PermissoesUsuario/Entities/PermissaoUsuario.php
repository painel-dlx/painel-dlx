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

namespace PainelDLX\Domain\PermissoesUsuario\Entities;


use DLX\Domain\Entities\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PainelDLX\Domain\Common\Entities\LogRegistroTrait;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Exceptions\PermissaoUsuarioJaPossuiGrupoException;

/**
 * Class PermissaoUsuario
 * @package PainelDLX\Domain\PermissoesUsuario\Entities
 * @covers PermissaoUsuarioTest
 */
class PermissaoUsuario extends Entity
{
    const TABELA_BD = 'PermissaoUsuario';

    use LogRegistroTrait;

    /** @var int */
    private $id;
    /** @var string */
    private $alias;
    /** @var string */
    private $descricao;
    /** @var ArrayCollection */
    private $grupos;
    /** @var bool */
    private $deletado = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return PermissaoUsuario
     */
    public function setId(int $id): PermissaoUsuario
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     * @return PermissaoUsuario
     */
    public function setAlias(string $alias): PermissaoUsuario
    {
        $this->alias = str_replace(' ',  '_', strtoupper($alias));
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     * @return PermissaoUsuario
     */
    public function setDescricao(string $descricao): PermissaoUsuario
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getGrupos(): Collection
    {
        return $this->grupos;
    }

    /**
     * @param GrupoUsuario $grupo_usuario
     * @return PermissaoUsuario
     * @throws PermissaoUsuarioJaPossuiGrupoException
     */
    public function addGrupo(GrupoUsuario $grupo_usuario): PermissaoUsuario
    {
        if ($this->hasGrupo($grupo_usuario)) {
            throw new PermissaoUsuarioJaPossuiGrupoException($grupo_usuario->getNome());
        }

        $this->grupos->add($grupo_usuario);
        return $this;
    }

    /**
     * @return bool
     */
    public function isDeletado(): bool
    {
        return $this->deletado;
    }

    /**
     * @param bool $deletado
     * @return PermissaoUsuario
     */
    public function setDeletado(bool $deletado): PermissaoUsuario
    {
        $this->deletado = $deletado;
        return $this;
    }

    /**
     * PermissaoUsuario constructor.
     * @param string $alias
     * @param string $descricao
     */
    public function __construct(string $alias, string $descricao)
    {
        $this->setAlias($alias);
        $this->setDescricao($descricao);
        $this->grupos = new ArrayCollection();
    }

    /**
     * @param string $alias
     * @param string $descricao
     * @return PermissaoUsuario
     * @deprecated
     */
    public static function create(string $alias, string $descricao)
    {
        return new self($alias, $descricao);
    }

    /**
     * Verificar se a entidade já possui um determinado grupo.
     * @param GrupoUsuario $grupo_usuario
     * @return bool
     */
    public function hasGrupo(GrupoUsuario $grupo_usuario)
    {
        return $this->grupos->contains($grupo_usuario);
    }
}