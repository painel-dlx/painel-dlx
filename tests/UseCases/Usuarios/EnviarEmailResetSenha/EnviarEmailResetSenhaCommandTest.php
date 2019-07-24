<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 13:58
 */

namespace PainelDLX\Testes\Application\UseCases\Usuarios\EnviarEmailResetSenha;

use DateTime;
use Exception;
use PainelDLX\UseCases\Usuarios\EnviarEmailResetSenha\EnviarEmailResetSenhaCommand;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PHPUnit\Framework\TestCase;

/**
 * Class EnviarEmailResetSenhaCommandTest
 * @package PainelDLX\Testes\Application\UseCases\Usuarios\EnviarEmailResetSenha
 * @coversDefaultClass \PainelDLX\UseCases\Usuarios\EnviarEmailResetSenha\EnviarEmailResetSenhaCommand
 */
class EnviarEmailResetSenhaCommandTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_GetResetSenha()
    {
        $reset_senha = (new ResetSenha())
            ->setData(new DateTime())
            ->setUsuario(new Usuario('Diego', 'dlepera88@gmail.com'));

        $command = new EnviarEmailResetSenhaCommand($reset_senha);

        $this->assertInstanceOf(ResetSenha::class, $command->getResetSenha());
        $this->assertEquals($reset_senha, $command->getResetSenha());
    }
}
