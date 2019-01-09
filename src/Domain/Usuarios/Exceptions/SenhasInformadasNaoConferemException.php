<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 05/12/2018
 * Time: 14:11
 */

namespace PainelDLX\Domain\Usuarios\Exceptions;


use DLX\Core\Exceptions\UserException;

class SenhasInformadasNaoConferemException extends UserException
{
    public function __construct()
    {
        parent::__construct('As senhas informadas não conferem!', 403);
    }
}