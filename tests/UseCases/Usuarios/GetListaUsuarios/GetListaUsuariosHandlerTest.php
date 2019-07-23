<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 06/02/2019
 * Time: 14:45
 */

namespace PainelDLX\Testes\Application\UseCases\Usuarios\GetListaUsuarios;

use DLX\Infrastructure\EntityManagerX;
use Doctrine\Common\Collections\ArrayCollection;
use PainelDLX\Tests\TestCase\PainelDLXTestCase;
use PainelDLX\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommand;
use PainelDLX\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommandHandler;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;

/**
 * Class GetListaUsuariosHandlerTest
 * @package PainelDLX\Testes\Application\UseCases\Usuarios\GetListaUsuarios
 * @coversDefaultClass \PainelDLX\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommandHandler
 */
class GetListaUsuariosHandlerTest extends PainelDLXTestCase
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

    /**
     * @covers ::handle
     */
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

    /**
     * @covers ::handle
     */
    public function test_Handle_com_criteria()
    {
        $criteria = ['nome' => 'Usuário'];

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
