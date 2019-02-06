<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 06/02/2019
 * Time: 14:45
 */

namespace PainelDLX\Testes\Application\UseCases\Usuarios\GetListaUsuarios;

use DLX\Infra\EntityManagerX;
use PainelDLX\Application\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommand;
use PainelDLX\Application\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosHandler;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Testes\PainelDLXTests;

class GetListaUsuariosHandlerTest extends PainelDLXTests
{
    /** @var UsuarioRepositoryInterface */
    private $usuario_repository;
    /** @var GetListaUsuariosHandler */
    private $handler;

    protected function setUp()
    {
        parent::setUp();

        $this->usuario_repository = EntityManagerX::getRepository(Usuario::class);
        $this->handler = new GetListaUsuariosHandler($this->usuario_repository);
    }

    public function test_Handle_sem_criteria()
    {
        $command = new GetListaUsuariosCommand();
        $lista_usuarios_command = $this->handler->handle($command);
        $lista_usuarios_repository = $this->usuario_repository->findBy([]);

        $this->assertEquals($lista_usuarios_repository, $lista_usuarios_command);
    }

    public function test_Handle_com_criteria()
    {
        $criteria = ['nome' => 'Diego Lepera'];

        $command = new GetListaUsuariosCommand($criteria);
        $lista_usuarios_command = $this->handler->handle($command);
        $lista_usuarios_repository = $this->usuario_repository->findBy($criteria);

        $this->assertEquals($lista_usuarios_repository, $lista_usuarios_command);
    }
}
