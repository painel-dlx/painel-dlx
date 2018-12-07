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

namespace PainelDLX\Domain\CadastroUsuarios\Entities;


use DLX\Domain\Entities\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class GrupoUsuario extends Entity
{
    /** @var int */
    private $grupo_usuario_id;
    /** @var string */
    private $alias;
    /** @var string */
    private $nome;
    /** @var bool */
    private $deletado = false;
    /** @var Collection */
    private $usuarios;

    /**
     * @return int
     */
    public function getGrupoUsuarioId(): int
    {
        return $this->grupo_usuario_id;
    }

    /**
     * @param int $grupo_usuario_id
     * @return GrupoUsuario
     */
    public function setGrupoUsuarioId(int $grupo_usuario_id): GrupoUsuario
    {
        $this->grupo_usuario_id = $grupo_usuario_id;
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
     * @return GrupoUsuario
     */
    public function setAlias(string $alias): GrupoUsuario
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return GrupoUsuario
     */
    public function setNome(string $nome): GrupoUsuario
    {
        $this->nome = $nome;
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
     * @return GrupoUsuario
     */
    public function setDeletado(bool $deletado): GrupoUsuario
    {
        $this->deletado = $deletado;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    /**
     * @param Usuario $usuario
     * @return GrupoUsuario
     */
    public function addUsuario(Usuario $usuario): GrupoUsuario
    {
        $this->usuarios->add($usuario);
        return $this;
    }

    /**
     * GrupoUsuario constructor.
     */
    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
    }

    /**
     * @param string $nome
     */
    public static function create(string $nome)
    {
        return (new self())
            ->setNome($nome)
            ->setAlias(self::gerarAliasApartirNome($nome));
    }

    public function __toString()
    {
        return $this->getNome();
    }

    /**
     * Gerar um alias de acordo com o nome informado
     * @param string $nome
     * @return string
     */
    public static function gerarAliasApartirNome(string $nome): string
    {
        return mb_strtoupper(str_replace(' ', '_', $nome));
    }
}
