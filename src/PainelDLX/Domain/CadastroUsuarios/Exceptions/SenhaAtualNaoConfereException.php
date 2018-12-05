<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 05/12/2018
 * Time: 14:22
 */

namespace PainelDLX\Domain\CadastroUsuarios\Exceptions;


use DLX\Core\Exceptions\UserException;

class SenhaAtualNaoConfereException extends UserException
{
    public function __construct()
    {
        parent::__construct('A senha atual informada não confere!');
    }
}