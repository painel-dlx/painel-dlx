<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 06/02/2019
 * Time: 11:42
 */

namespace PainelDLX\UseCases\Usuarios\GetListaUsuarios;


use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommand;

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
     * @return array|null
     */
    public function handle(GetListaUsuariosCommand $command): ?array
    {
        $criteria = $command->getCriteria();
        $criteria['and'] = ['deletado' => false];

        return $this->usuario_repository->findByLike(
            $criteria,
            $command->getOrderBy(),
            $command->getLimit(),
            $command->getOffset()
        );
    }
}