<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 10/01/2019
 * Time: 12:10
 */

namespace PainelDLX\UseCases\Emails\NovaConfigSmtp;


class NovaConfigSmtpCommand
{
    /**
     * @var string
     */
    private $nome;
    /**
     * @var string
     */
    private $servidor;
    /**
     * @var int
     */
    private $porta;
    /**
     * @var string|null
     */
    private $cripto;
    /**
     * @var bool
     */
    private $requer_autent;
    /**
     * @var string|null
     */
    private $conta;
    /**
     * @var string|null
     */
    private $senha;
    /**
     * @var string|null
     */
    private $de_nome;
    /**
     * @var string|null
     */
    private $responder_para;
    /**
     * @var bool
     */
    private $corpo_html;

    /**
     * NovaConfigSmtpCommand constructor.
     * @param string $nome
     * @param string $servidor
     * @param int $porta
     * @param string|null $cripto
     * @param bool $requer_autent
     * @param string|null $conta
     * @param string|null $senha
     * @param string|null $de_nome
     * @param string|null $responder_para
     * @param bool $corpo_html
     */
    public function __construct(
        string $nome,
        string $servidor,
        int $porta,
        ?string $cripto,
        bool $requer_autent = false,
        ?string $conta = null,
        ?string $senha = null,
        ?string $de_nome = null,
        ?string $responder_para = null,
        bool $corpo_html = false
    ) {
        $this->nome = $nome;
        $this->servidor = $servidor;
        $this->porta = $porta;
        $this->cripto = $cripto;
        $this->requer_autent = $requer_autent;
        $this->conta = $conta;
        $this->senha = $senha;
        $this->de_nome = $de_nome;
        $this->responder_para = $responder_para;
        $this->corpo_html = $corpo_html;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @return string
     */
    public function getServidor(): string
    {
        return $this->servidor;
    }

    /**
     * @return int
     */
    public function getPorta(): int
    {
        return $this->porta;
    }

    /**
     * @return string|null
     */
    public function getCripto(): ?string
    {
        return $this->cripto;
    }

    /**
     * @return bool
     */
    public function isRequerAutent(): bool
    {
        return $this->requer_autent;
    }

    /**
     * @return string|null
     */
    public function getConta(): ?string
    {
        return $this->conta;
    }

    /**
     * @return string|null
     */
    public function getSenha(): ?string
    {
        return $this->senha;
    }

    /**
     * @return string|null
     */
    public function getDeNome(): ?string
    {
        return $this->de_nome;
    }

    /**
     * @return string|null
     */
    public function getResponderPara(): ?string
    {
        return $this->responder_para;
    }

    /**
     * @return bool
     */
    public function isCorpoHtml(): bool
    {
        return $this->corpo_html;
    }
}