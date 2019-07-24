<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 13:54
 */

namespace PainelDLX\Testes\Application\UseCases\Usuarios\SolicitarResetSenha;

use PainelDLX\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommand;
use PHPUnit\Framework\TestCase;

class SolicitarResetSenhaCommandTest extends TestCase
{
    public function test_getEmail()
    {
        $email = 'nome@dominio.com';
        $command = new SolicitarResetSenhaCommand($email);
        $this->assertEquals($email, $command->getEmail());
    }
}
