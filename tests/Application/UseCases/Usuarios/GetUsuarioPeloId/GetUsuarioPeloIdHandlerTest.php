<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/01/2019
 * Time: 17:59
 */

namespace PainelDLX\Testes\Application\UseCases\Usuarios\GetUsuarioPeloId;

use DLX\Infra\EntityManagerX;
use PainelDLX\Application\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommand;
use PainelDLX\Application\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdHandler;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Testes\PainelDLXTests;

class GetUsuarioPeloIdHandlerTest extends PainelDLXTests
{
    /** @var GetUsuarioPeloIdHandler */
    private $handler;

    protected function setUp()
    {
        parent::setUp();

        /** @var UsuarioRepositoryInterface $usuario_repository */
        $usuario_repository = EntityManagerX::getRepository(Usuario::class);
        $this->handler = new GetUsuarioPeloIdHandler($usuario_repository);
    }


    public function test_Handle_deve_retornar_null_quando_nao_encontra_Usuario()
    {
        $command = new GetUsuarioPeloIdCommand(0);
        $usuario = $this->handler->handle($command);

        $this->assertNull($usuario);
    }

    public function test_Handle_deve_retornar_instancia_Usuario()
    {
        $command = new GetUsuarioPeloIdCommand(2); // Diego Lepera
        $usuario = $this->handler->handle($command);

        $this->assertInstanceOf(Usuario::class, $usuario);
    }
}
