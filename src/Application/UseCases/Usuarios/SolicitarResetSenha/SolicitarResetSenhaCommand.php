<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 09:18
 */

namespace PainelDLX\Application\UseCases\Usuarios\SolicitarResetSenha;


class SolicitarResetSenhaCommand
{
    /**
     * @var string
     */
    private $email;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * SolicitarResetSenhaCommand constructor.
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }
}