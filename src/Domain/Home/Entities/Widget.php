<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 15/02/2019
 * Time: 08:47
 */

namespace PainelDLX\Domain\Home\Entities;


use DLX\Domain\Entities\Entity;

class Widget extends Entity
{
    /** @var null|int */
    private $id;
    /** @var string */
    private $titulo;
    /** @var string */
    private $url_conteudo;
    /** @var boolean */
    private $ativo = true;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Widget
     */
    public function setId(?int $id): Widget
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitulo(): string
    {
        return $this->titulo;
    }

    /**
     * @param string $titulo
     * @return Widget
     */
    public function setTitulo(string $titulo): Widget
    {
        $this->titulo = $titulo;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrlConteudo(): string
    {
        return $this->url_conteudo;
    }

    /**
     * @param string $url_conteudo
     * @return Widget
     */
    public function setUrlConteudo(string $url_conteudo): Widget
    {
        $this->url_conteudo = $url_conteudo;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    /**
     * @param bool $ativo
     * @return Widget
     */
    public function setAtivo(bool $ativo): Widget
    {
        $this->ativo = $ativo;
        return $this;
    }

    /**
     * Widget constructor.
     * @param string $titulo
     * @param string $url_conteudo
     */
    public function __construct(string $titulo, string $url_conteudo)
    {
        $this->titulo = $titulo;
        $this->url_conteudo = $url_conteudo;
    }

    public function __toString()
    {
        return $this->getTitulo();
    }
}