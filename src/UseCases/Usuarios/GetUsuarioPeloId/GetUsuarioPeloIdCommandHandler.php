<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/01/2019
 * Time: 17:58
 */

namespace PainelDLX\UseCases\Usuarios\GetUsuarioPeloId;


use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontradoException;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;

/**
 * Class GetUsuarioPeloIdCommandHandler
 * @package PainelDLX\UseCases\Usuarios\GetUsuarioPeloId
 * @covers GetUsuarioPeloIdCommandHandlerTest
 */
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
     * @throws UsuarioNaoEncontradoException
     */
    public function handle(GetUsuarioPeloIdCommand $command): ?Usuario
    {
        $usuario_id = $command->getId();

        /** @var Usuario|null $usuario */
        $usuario = $this->usuario_repository->find($usuario_id);

        if (is_null($usuario)) {
            throw UsuarioNaoEncontradoException::porId($usuario_id);
        }

        return $usuario;
    }
}