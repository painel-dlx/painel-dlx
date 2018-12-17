<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 05/12/2018
 * Time: 14:15
 */

namespace PainelDLX\Domain\CadastroUsuarios\ValueObjects;


class SenhaUsuario
{
    /** @var string|null */
    private $senha_atual;
    /** @var string */
    private $senha_informada;
    /** @var string */
    private $senha_confirm;

    /**
     * @return null|string
     */
    public function getSenhaAtual(): ?string
    {
        return $this->senha_atual;
    }

    /**
     * @param null|string $senha_atual
     * @return SenhaUsuario
     */
    public function setSenhaAtual(?string $senha_atual): SenhaUsuario
    {
        $this->senha_atual = $senha_atual;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenhaInformada(): string
    {
        return $this->senha_informada;
    }

    /**
     * @param string $senha_informada
     * @return SenhaUsuario
     */
    public function setSenhaInformada(string $senha_informada): SenhaUsuario
    {
        $this->senha_informada = $senha_informada;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenhaConfirm(): string
    {
        return $this->senha_confirm;
    }

    /**
     * @param string $senha_confirm
     * @return SenhaUsuario
     */
    public function setSenhaConfirm(string $senha_confirm): SenhaUsuario
    {
        $this->senha_confirm = $senha_confirm;
        return $this;
    }

    /**
     * SenhaUsuario constructor.
     * @param string $senha_informada
     * @param string $senha_confirm
     * @param null|string $senha_atual
     */
    public function __construct(string $senha_informada, string $senha_confirm, ?string $senha_atual = null)
    {
        $this->setSenhaInformada($senha_informada);
        $this->setSenhaConfirm($senha_confirm);
        $this->setSenhaAtual($senha_atual);
    }
}