<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 10/01/2019
 * Time: 07:45
 */

namespace PainelDLX\Domain\Emails\Entities;


use DLX\Domain\Entities\Entity;
use PainelDLX\Domain\Common\Entities\LogRegistroTrait;

class ConfigSmtp extends Entity
{
    const TABELA_BD = 'dlx_config_smtp';

    use LogRegistroTrait;

    /** @var int|null */
    private $id;
    /** @var string|null */
    private $nome;
    /** @var string */
    private $servidor = 'localhost';
    /** @var int */
    private $porta = 25;
    /** @var string|null */
    private $cripto;
    /** @var bool */
    private $requer_autent = false;
    /** @var string|null */
    private $conta;
    /** @var string|null */
    private $senha;
    /** @var string|null */
    private $de_nome;
    /** @var string|null */
    private $responder_para;
    /** @var bool */
    private $corpo_html = false;
    /** @var bool */
    private $deletado = false;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return ConfigSmtp
     */
    public function setId(?int $id): ConfigSmtp
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNome(): ?string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return ConfigSmtp
     */
    public function setNome(string $nome): ConfigSmtp
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getServidor(): string
    {
        return $this->servidor;
    }

    /**
     * @param string $servidor
     * @return ConfigSmtp
     */
    public function setServidor(string $servidor): ConfigSmtp
    {
        $this->servidor = $servidor;
        return $this;
    }

    /**
     * @return int
     */
    public function getPorta(): int
    {
        return $this->porta;
    }

    /**
     * @param int $porta
     * @return ConfigSmtp
     */
    public function setPorta(int $porta): ConfigSmtp
    {
        $this->porta = filter_var($porta, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 25, 'max_range' => 65535, 'default' => 25]
        ]);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCripto(): ?string
    {
        return $this->cripto;
    }

    /**
     * @param string|null $cripto
     * @return ConfigSmtp
     */
    public function setCripto(?string $cripto): ConfigSmtp
    {
        $this->cripto = filter_var($cripto, FILTER_VALIDATE_REGEXP, [
            'options' => ['regexp' => '~(ssl|tls)~'],
            'flags' => FILTER_NULL_ON_FAILURE
        ]);
        return $this;
    }

    /**
     * @return bool
     */
    public function isRequerAutent(): bool
    {
        return $this->requer_autent;
    }

    /**
     * @param bool $requer_autent
     * @return ConfigSmtp
     */
    public function setRequerAutent(bool $requer_autent): ConfigSmtp
    {
        $this->requer_autent = $requer_autent;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getConta(): ?string
    {
        return $this->conta;
    }

    /**
     * @param string|null $conta
     * @return ConfigSmtp
     */
    public function setConta(?string $conta): ConfigSmtp
    {
        $this->conta = $conta;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSenha(): ?string
    {
        return $this->senha;
    }

    /**
     * @param string|null $senha
     * @return ConfigSmtp
     */
    public function setSenha(?string $senha): ConfigSmtp
    {
        $this->senha = $senha;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeNome(): ?string
    {
        return $this->de_nome;
    }

    /**
     * @param string|null $de_nome
     * @return ConfigSmtp
     */
    public function setDeNome(?string $de_nome): ConfigSmtp
    {
        $this->de_nome = $de_nome;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getResponderPara(): ?string
    {
        return $this->responder_para;
    }

    /**
     * @param string|null $responder_para
     * @return ConfigSmtp
     */
    public function setResponderPara(?string $responder_para): ConfigSmtp
    {
        $this->responder_para = filter_var($responder_para, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);
        return $this;
    }

    /**
     * @return bool
     */
    public function isCorpoHtml(): bool
    {
        return $this->corpo_html;
    }

    /**
     * @param bool $corpo_html
     * @return ConfigSmtp
     */
    public function setCorpoHtml(bool $corpo_html): ConfigSmtp
    {
        $this->corpo_html = $corpo_html;
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
     * @return ConfigSmtp
     */
    public function setDeletado(bool $deletado): ConfigSmtp
    {
        $this->deletado = $deletado;
        return $this;
    }

    /**
     * ConfigSmtp constructor.
     * @param string $servidor
     * @param int $porta
     */
    public function __construct(string $servidor = 'localhost', int $porta = 25)
    {
        $this->setServidor($servidor);
        $this->setPorta($porta);
    }

    public function __toString()
    {
        return "{$this->getServidor()}:{$this->getPorta()}";
    }
}