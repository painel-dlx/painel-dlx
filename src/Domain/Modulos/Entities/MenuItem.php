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

namespace PainelDLX\Domain\Modulos\Entities;


use DLX\Domain\Entities\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;

class MenuItem extends Entity
{
    /** @var int|null */
    private $id;
    /** @var Menu */
    private $menu;
    /** @var string */
    private $nome;
    /** @var string */
    private $link;
    /** @var Collection */
    private $permissoes;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return MenuItem
     */
    public function setId(?int $id): MenuItem
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Menu
     */
    public function getMenu(): Menu
    {
        return $this->menu;
    }

    /**
     * @param Menu $menu
     * @return MenuItem
     */
    public function setMenu(Menu $menu): MenuItem
    {
        $this->menu = $menu;
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
     * @return MenuItem
     */
    public function setNome(string $nome): MenuItem
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return MenuItem
     */
    public function setLink(string $link): MenuItem
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getPermissoes(): Collection
    {
        return $this->permissoes;
    }

    /**
     * @param MenuItemPermissao
     */
    public function addPermissao(MenuItemPermissao $menu_item_permissao): MenuItem
    {
        $this->permissoes->add($menu_item_permissao);
        return $this;
    }

    /**
     * MenuItem constructor.
     * @param Menu $menu
     * @param string $nome
     * @param string $link
     */
    public function __construct(Menu $menu, string $nome, string $link)
    {
        $this->menu = $menu;
        $this->nome = $nome;
        $this->link = $link;
        $this->permissoes = new ArrayCollection();
    }
}