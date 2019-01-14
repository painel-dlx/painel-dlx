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
    private $reset_senha_id;
    /** @var Usuario */
    private $usuario;
    /** @var DateTime */
    private $data;
    /** @var string */
    private $hash;

    /**
     * @return int|null
     */
    public function getResetSenhaId(): ?int
    {
        return $this->reset_senha_id;
    }

    /**
     * @param int|null $reset_senha_id
     * @return ResetSenha
     */
    public function setResetSenhaId(?int $reset_senha_id): ResetSenha
    {
        $this->reset_senha_id = $reset_senha_id;
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
     * @throws Exception
     */
    public function isExpirado(): bool
    {
        $hoje = new DateTime();
        $limite = (clone $this->getData())->modify('+5 days');

        return $hoje <= $limite;
    }
}