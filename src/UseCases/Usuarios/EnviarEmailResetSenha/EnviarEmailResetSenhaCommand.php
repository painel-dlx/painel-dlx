<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 09:33
 */

namespace PainelDLX\UseCases\Usuarios\EnviarEmailResetSenha;


use PainelDLX\Domain\Usuarios\Entities\ResetSenha;

class EnviarEmailResetSenhaCommand
{
    /**
     * @var ResetSenha
     */
    private $reset_senha;

    /**
     * @return ResetSenha
     */
    public function getResetSenha(): ResetSenha
    {
        return $this->reset_senha;
    }

    /**
     * EnviarEmailResetSenhaCommand constructor.
     * @param ResetSenha $reset_senha
     */
    public function __construct(ResetSenha $reset_senha)
    {
        $this->reset_senha = $reset_senha;
    }
}