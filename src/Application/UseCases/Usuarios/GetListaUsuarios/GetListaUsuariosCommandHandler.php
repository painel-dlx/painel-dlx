<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 06/02/2019
 * Time: 11:42
 */

namespace PainelDLX\Application\UseCases\Usuarios\GetListaUsuarios;


use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;

/**
 * Class GetListaUsuariosCommandHandler
 * @package PainelDLX\Application\UseCases\Usuarios\GetListaUsuarios
 * @covers GetListaUsuariosHandlerTest
 */
class GetListaUsuariosCommandHandler
{
    /**
     * @var UsuarioRepositoryInterface
     */
    private $usuario_repository;

    /**
     * GetListaUsuariosCommandHandler constructor.
     * @param UsuarioRepositoryInterface $usuario_repository
     */
    public function __construct(UsuarioRepositoryInterface $usuario_repository)
    {
        $this->usuario_repository = $usuario_repository;
    }

    /**
     * @param GetListaUsuariosCommand $command
     */
    public function handle(GetListaUsuariosCommand $command): ?array
    {
        $lista = $this->usuario_repository->findByLike($command->getCriteria(), $command->getOrderBy(), $command->getLimit(), $command->getOffset());
        return array_filter($lista, function (Usuario $usuario) { return !$usuario->isDeletado(); });
    }
}