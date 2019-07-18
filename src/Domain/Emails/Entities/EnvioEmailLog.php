<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 10/01/2019
 * Time: 10:15
 */

namespace PainelDLX\Domain\Emails\Entities;


use DateTime;
use DLX\Domain\Entities\Entity;

class EnvioEmailLog extends Entity
{
    /** @var int|null */
    private $id;
    /** @var DateTime */
    private $data;
    /** @var ConfigSmtp */
    private $config_smtp;
    /** @var string */
    private $para;
    /** @var string|null */
    private $cc;
    /** @var string|null */
    private $cco;
    /** @var string */
    private $assunto;
    /** @var string */
    private $corpo;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return EnvioEmailLog
     */
    public function setId(?int $id): EnvioEmailLog
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getData(): DateTime
    {
        return $this->data;
    }

    /**
     * @param DateTime $data
     * @return EnvioEmailLog
     */
    public function setData(DateTime $data): EnvioEmailLog
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return ConfigSmtp
     */
    public function getConfigSmtp(): ConfigSmtp
    {
        return $this->config_smtp;
    }

    /**
     * @param ConfigSmtp $config_smtp
     * @return EnvioEmailLog
     */
    public function setConfigSmtp(ConfigSmtp $config_smtp): EnvioEmailLog
    {
        $this->config_smtp = $config_smtp;
        return $this;
    }

    /**
     * @return string
     */
    public function getPara(): string
    {
        return $this->para;
    }

    /**
     * @param string $para
     * @return EnvioEmailLog
     */
    public function setPara(string $para): EnvioEmailLog
    {
        $this->para = $para;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCc(): ?string
    {
        return $this->cc;
    }

    /**
     * @param string|null $cc
     * @return EnvioEmailLog
     */
    public function setCc(?string $cc): EnvioEmailLog
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCco(): ?string
    {
        return $this->cco;
    }

    /**
     * @param string|null $cco
     * @return EnvioEmailLog
     */
    public function setCco(?string $cco): EnvioEmailLog
    {
        $this->cco = $cco;
        return $this;
    }

    /**
     * @return string
     */
    public function getAssunto(): string
    {
        return $this->assunto;
    }

    /**
     * @param string $assunto
     * @return EnvioEmailLog
     */
    public function setAssunto(string $assunto): EnvioEmailLog
    {
        $this->assunto = $assunto;
        return $this;
    }

    /**
     * @return string
     */
    public function getCorpo(): string
    {
        return $this->corpo;
    }

    /**
     * @param string $corpo
     * @return EnvioEmailLog
     */
    public function setCorpo(string $corpo): EnvioEmailLog
    {
        $this->corpo = $corpo;
        return $this;
    }
}