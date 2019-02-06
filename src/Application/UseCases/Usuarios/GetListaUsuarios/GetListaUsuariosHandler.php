<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 06/02/2019
 * Time: 11:42
 */

namespace PainelDLX\Application\UseCases\Usuarios\GetListaUsuarios;


use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;

class GetListaUsuariosHandler
{
    /**
     * @var UsuarioRepositoryInterface
     */
    private $usuario_repository;

    /**
     * GetListaUsuariosHandler constructor.
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
        return $this->usuario_repository->findBy($command->getCriteria(), $command->getOrderBy(), $command->getLimit(), $command->getOffset());
    }
}