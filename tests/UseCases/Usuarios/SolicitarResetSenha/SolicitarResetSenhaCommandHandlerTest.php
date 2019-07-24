<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 15:34
 */

namespace PainelDLX\Testes\Application\UseCases\Usuarios\SolicitarResetSenha;

use DLX\Infrastructure\EntityManagerX;
use Doctrine\ORM\ORMException;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioJaPossuiGrupoException;
use PainelDLX\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommand;
use PainelDLX\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommandHandler;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontradoException;
use PainelDLX\Domain\Usuarios\Repositories\ResetSenhaRepositoryInterface;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class SolicitarResetSenhaHandlerTest
 * @package PainelDLX\Testes\Application\UseCases\Usuarios\SolicitarResetSenha
 * @coversDefaultClass \PainelDLX\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommandHandler
 */
class SolicitarResetSenhaCommandHandlerTest extends TestCase
{
    /**
     * @return void
     * @throws UsuarioJaPossuiGrupoException
     * @throws UsuarioNaoEncontradoException
     * @covers ::handle
     */
    public function test_Handle_deve_criar_uma_solicitacao_de_ResetSenha()
    {
        $reset_senha_repository = $this->createMock(ResetSenhaRepositoryInterface::class);
        $reset_senha_repository->method('findResetSenhaAtivoPorUsuario')->willReturn(null);

        // Criar um usuário para poder pedir o reset de senha
        $usuario = new Usuario('Diego Lepera', 'dlepera88.emails@gmail.com');
        $usuario->setSenha('345nesf87p1AS');

        $usuario_repository = $this->createMock(UsuarioRepositoryInterface::class);
        $usuario_repository->method('findBy')->willReturn([$usuario]);

        /** @var UsuarioRepositoryInterface $usuario_repository */
        /** @var ResetSenhaRepositoryInterface $reset_senha_repository */

        $command = new SolicitarResetSenhaCommand('dlepera88.emails@gmail.com');
        $handler = new SolicitarResetSenhaCommandHandler($reset_senha_repository, $usuario_repository);
        $reset_senha = $handler->handle($command);

        $this->assertInstanceOf(ResetSenha::class, $reset_senha);
        $this->assertEquals($usuario, $reset_senha->getUsuario());
    }

    /**
     * @return void
     * @throws UsuarioJaPossuiGrupoException
     * @throws UsuarioNaoEncontradoException
     * @covers ::handle
     */
    public function test_Handle_nao_deve_criar_novo_ResetSenha_caso_exista_um_ativo(): void
    {
        $hash = uniqid();

        // Criar um usuário para poder pedir o reset de senha
        $usuario = new Usuario('Diego Lepera', 'dlepera88.emails@gmail.com');
        $usuario->setSenha('345nesf87p1AS');

        $reset_senha_existente = new ResetSenha();
        $reset_senha_existente->setUsuario($usuario);
        $reset_senha_existente->setHash($hash);

        $reset_senha_repository = $this->createMock(ResetSenhaRepositoryInterface::class);
        $reset_senha_repository->method('findResetSenhaAtivoPorUsuario')->willReturn($reset_senha_existente);

        $usuario_repository = $this->createMock(UsuarioRepositoryInterface::class);
        $usuario_repository->method('findBy')->willReturn([$usuario]);

        /** @var UsuarioRepositoryInterface $usuario_repository */
        /** @var ResetSenhaRepositoryInterface $reset_senha_repository */

        $command = new SolicitarResetSenhaCommand('dlepera88.emails@gmail.com');
        $handler = new SolicitarResetSenhaCommandHandler($reset_senha_repository, $usuario_repository);
        $reset_senha_retornado = $handler->handle($command);

        $this->assertSame($reset_senha_existente, $reset_senha_retornado);
    }

    /**
     * @covers ::handle
     * @throws UsuarioNaoEncontradoException
     */
    public function test_Handle_deve_lancar_excecao_quando_nao_encontrar_Usuario_pelo_email()
    {
        $usuario_repository = $this->createMock(UsuarioRepositoryInterface::class);
        $usuario_repository->method('findBy')->willReturn(null);

        $reset_senha_repository = $this->createMock(ResetSenhaRepositoryInterface::class);

        /** @var UsuarioRepositoryInterface $usuario_repository */
        /** @var ResetSenhaRepositoryInterface $reset_senha_repository */

        $this->expectException(UsuarioNaoEncontradoException::class);
        $this->expectExceptionCode(11);

        $command = new SolicitarResetSenhaCommand('dlepera88.emails@gmail.com');
        $handler = new SolicitarResetSenhaCommandHandler($reset_senha_repository, $usuario_repository);
        $handler->handle($command);
    }
}
