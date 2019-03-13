<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 15:34
 */

namespace PainelDLX\Testes\Application\UseCases\Usuarios\SolicitarResetSenha;

use DLX\Infra\EntityManagerX;
use PainelDLX\Application\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommand;
use PainelDLX\Application\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommandHandler;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\ResetSenhaRepositoryInterface;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Testes\PainelDLXTests;

class SolicitarResetSenhaHandlerTest extends PainelDLXTests
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontrado
     */
    public function test_Handle(): ResetSenha
    {
        /** @var ResetSenhaRepositoryInterface $reset_senha_repository */
        $reset_senha_repository = EntityManagerX::getRepository(ResetSenha::class);

        // Criar um usuário para poder pedir o reset de senha
        $usuario = new Usuario('Diego Lepera', 'dlepera88.emails@gmail.com');
        $usuario->setSenha('345nesf87p1AS');
        $reset_senha_repository->create($usuario);

        $usuario_repository = $this->createMock(UsuarioRepositoryInterface::class);
        $usuario_repository
            ->method('findBy')
            ->willReturn([$usuario]);

        $command = new SolicitarResetSenhaCommand('dlepera88.emails@gmail.com');

        /** @var UsuarioRepositoryInterface $usuario_repository */
        $handler = new SolicitarResetSenhaCommandHandler($reset_senha_repository, $usuario_repository);
        $reset_senha = $handler->handle($command);

        $this->assertInstanceOf(ResetSenha::class, $reset_senha);
        $this->assertNotNull($reset_senha->getResetSenhaId());

        return $reset_senha;
    }

    /**
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontrado
     */
    public function test_Handle_nao_deve_criar_novo_ResetSenha_caso_exista_um_ativo(): void
    {
        /** @var ResetSenhaRepositoryInterface $reset_senha_repository */
        $reset_senha_repository = EntityManagerX::getRepository(ResetSenha::class);

        // Criar um usuário para poder pedir o reset de senha
        $usuario = new Usuario('Diego Lepera', 'dlepera88.emails@gmail.com');
        $usuario->setSenha('345nesf87p1AS');
        $reset_senha_repository->create($usuario);

        $usuario_repository = $this->createMock(UsuarioRepositoryInterface::class);
        $usuario_repository
            ->method('findBy')
            ->willReturn([$usuario]);

        $command = new SolicitarResetSenhaCommand('dlepera88.emails@gmail.com');

        // Executo 2 vezes o handler abaixo para verificar se foi criado um ResetSenha novo ou não

        /** @var UsuarioRepositoryInterface $usuario_repository */
        $handler = new SolicitarResetSenhaCommandHandler($reset_senha_repository, $usuario_repository);
        $reset_senha = $handler->handle($command);

        $handler = new SolicitarResetSenhaCommandHandler($reset_senha_repository, $usuario_repository);
        $reset_senha2 = $handler->handle($command);

        $this->assertEquals($reset_senha, $reset_senha2);
    }
}
