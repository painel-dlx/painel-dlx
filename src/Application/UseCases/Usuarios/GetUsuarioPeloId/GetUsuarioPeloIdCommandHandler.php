<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/01/2019
 * Time: 17:58
 */

namespace PainelDLX\Application\UseCases\Usuarios\GetUsuarioPeloId;


use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;

class GetUsuarioPeloIdCommandHandler
{
    /**
     * @var UsuarioRepositoryInterface
     */
    private $usuario_repository;

    /**
     * GetUsuarioPeloIdCommandHandler constructor.
     * @param UsuarioRepositoryInterface $usuario_repository
     */
    public function __construct(UsuarioRepositoryInterface $usuario_repository)
    {
        $this->usuario_repository = $usuario_repository;
    }

    /**
     * @param GetUsuarioPeloIdCommand $command
     * @return Usuario|null
     */
    public function handle(GetUsuarioPeloIdCommand $command): ?Usuario
    {
        return $this->usuario_repository->find($command->getUsuarioId());
    }
}