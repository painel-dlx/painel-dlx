<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 09:18
 */

namespace PainelDLX\Application\UseCases\Usuarios\SolicitarResetSenha;


use DateTime;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontrado;
use PainelDLX\Domain\Usuarios\Repositories\ResetSenhaRepositoryInterface;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;

class SolicitarResetSenhaHandler
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
     * SolicitarResetSenhaHandler constructor.
     * @param ResetSenhaRepositoryInterface $reset_senha_repository
     * @param UsuarioRepositoryInterface $usuario_repository
     * @param ConfigSmtpRepositoryInterface $config_smtp_repository
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
     * @throws UsuarioNaoEncontrado
     * @throws \Exception
     */
    public function handle(SolicitarResetSenhaCommand $command): ResetSenha
    {
        $lista_usuarios = $this->usuario_repository->findBy(['email' => $command->getEmail()]);

        if (count($lista_usuarios) < 1) {
            throw new UsuarioNaoEncontrado();
        }

        $usuario = current($lista_usuarios);

        // Gravar a solicitação
        // Antes verificar se existe uma solicitação ativa
        $reset_senha = $this->reset_senha_repository->findResetSenhaAtivo($usuario);

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