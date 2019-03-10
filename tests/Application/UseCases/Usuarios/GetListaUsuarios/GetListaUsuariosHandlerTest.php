<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 06/02/2019
 * Time: 14:45
 */

namespace PainelDLX\Testes\Application\UseCases\Usuarios\GetListaUsuarios;

use DLX\Infra\EntityManagerX;
use Doctrine\Common\Collections\ArrayCollection;
use PainelDLX\Application\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommand;
use PainelDLX\Application\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommandHandler;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Testes\PainelDLXTests;

class GetListaUsuariosHandlerTest extends PainelDLXTests
{
    /** @var UsuarioRepositoryInterface */
    private $usuario_repository;
    /** @var GetListaUsuariosCommandHandler */
    private $handler;

    protected function setUp()
    {
        parent::setUp();

        $this->usuario_repository = EntityManagerX::getRepository(Usuario::class);
        $this->handler = new GetListaUsuariosCommandHandler($this->usuario_repository);
    }

    public function test_Handle_sem_criteria()
    {
        $command = new GetListaUsuariosCommand();
        $lista_usuarios_command = $this->handler->handle($command);
        $lista_usuarios_repository = $this->usuario_repository->findBy(['deletado' => false]);

        $this->assertEquals($lista_usuarios_repository, $lista_usuarios_command);

        $lista_usuarios_command_collection = new ArrayCollection($lista_usuarios_command);

        // Não pode trazer registros que estão marcados como deletado
        $this->assertFalse($lista_usuarios_command_collection->exists(function ($key, Usuario $usuario) {
            return $usuario->isDeletado();
        }));
    }

    public function test_Handle_com_criteria()
    {
        $criteria = ['nome' => 'Novo Usuário'];

        $command = new GetListaUsuariosCommand($criteria);
        $lista_usuarios_command = $this->handler->handle($command);
        $lista_usuarios_repository = array_filter(
            $this->usuario_repository->findByLike($criteria),
            function (Usuario $usuario) {
                return !$usuario->isDeletado();
            }
        );

        $this->assertEquals($lista_usuarios_repository, $lista_usuarios_command);

        $lista_usuarios_command_collection = new ArrayCollection($lista_usuarios_command);

        // Não pode trazer registros que estão marcados como deletado
        $this->assertFalse($lista_usuarios_command_collection->exists(function ($key, Usuario $usuario) {
            return $usuario->isDeletado();
        }));
    }
}
