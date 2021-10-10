<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 09:18
 */

namespace PainelDLX\UseCases\Usuarios\SolicitarResetSenha;


use DateTime;
use Exception;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontradoException;
use PainelDLX\Domain\Usuarios\Repositories\ResetSenhaRepositoryInterface;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommand;

/**
 * Class SolicitarResetSenhaCommandHandler
 * @package PainelDLX\UseCases\Usuarios\SolicitarResetSenha
 * @covers SolicitarResetSenhaCommandHandlerTest
 */
class SolicitarResetSenhaCommandHandler
{
    /**
     * @var ResetSenhaRepositoryInterface
     */
    private $reset_senha_repository;
    /**
     * @var UsuarioRepositoryInterface
     */
    private $usuario_repository;

    /**
     * SolicitarResetSenhaCommandHandler constructor.
     * @param ResetSenhaRepositoryInterface $reset_senha_repository
     * @param UsuarioRepositoryInterface $usuario_repository
     */
    public function __construct(
        ResetSenhaRepositoryInterface $reset_senha_repository,
        UsuarioRepositoryInterface $usuario_repository
    ) {
        $this->reset_senha_repository = $reset_senha_repository;
        $this->usuario_repository = $usuario_repository;
    }

    /**
     * @param SolicitarResetSenhaCommand $command
     * @return ResetSenha
     * @throws UsuarioNaoEncontradoException
     * @throws Exception
     */
    public function handle(SolicitarResetSenhaCommand $command): ResetSenha
    {
        $email = $command->getEmail();

        $lista_usuarios = $this->usuario_repository->findBy(['email' => $email]);

        if (is_null($lista_usuarios) || count($lista_usuarios) < 1) {
            throw UsuarioNaoEncontradoException::porEmail($email);
        }

        $usuario = current($lista_usuarios);

        // Gravar a solicitação
        // Antes verificar se existe uma solicitação ativa
        $reset_senha = $this->reset_senha_repository->findResetSenhaAtivoPorUsuario($usuario);

        // Se não houver, cria uma nova
        if (is_null($reset_senha))  {
            $reset_senha = (new ResetSenha())
                ->setUsuario($usuario)
                ->setData(new DateTime())
                ->gerarHash();
        }

        $this->reset_senha_repository->create($reset_senha);

        return $reset_senha;
    }
}