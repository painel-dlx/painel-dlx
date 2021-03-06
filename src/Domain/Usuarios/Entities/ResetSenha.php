<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 08:33
 */

namespace PainelDLX\Domain\Usuarios\Entities;


use DateTime;
use DLX\Domain\Entities\Entity;
use Exception;

class ResetSenha extends Entity
{
    /** @var int|null */
    private $id;
    /** @var Usuario */
    private $usuario;
    /** @var DateTime */
    private $data;
    /** @var string */
    private $hash;
    /** @var bool */
    private $utilizado = false;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return ResetSenha
     */
    public function setId(?int $id): ResetSenha
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Usuario
     */
    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    /**
     * @param Usuario $usuario
     * @return ResetSenha
     */
    public function setUsuario(Usuario $usuario): ResetSenha
    {
        $this->usuario = $usuario;
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
     * @return ResetSenha
     */
    public function setData(DateTime $data): ResetSenha
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return ResetSenha
     */
    public function setHash(string $hash): ResetSenha
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUtilizado(): bool
    {
        return $this->utilizado;
    }

    /**
     * @param bool $utilizado
     * @return ResetSenha
     */
    public function setUtilizado(bool $utilizado): ResetSenha
    {
        $this->utilizado = $utilizado;
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isExpirado(): bool
    {
        $hoje = new DateTime();
        $limite = (clone $this->getData())->modify('+5 days');

        return $hoje > $limite;
    }

    /**
     * @return ResetSenha
     * @throws Exception
     */
    public function gerarHash(): ResetSenha
    {
        $this->hash = uniqid('pdlx');
        return $this;
    }
}