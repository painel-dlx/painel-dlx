<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/01/2019
 * Time: 17:57
 */

namespace PainelDLX\Testes\Application\UseCases\Usuarios\GetUsuarioPeloId;

use PainelDLX\Application\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommand;
use PHPUnit\Framework\TestCase;

class GetUsuarioPeloIdCommandTest extends TestCase
{

    public function test_GetUsuarioId()
    {
        $usuario_id = 1;
        $command = new GetUsuarioPeloIdCommand($usuario_id);

        $this->assertEquals($usuario_id, $command->getUsuarioId());
    }
}
