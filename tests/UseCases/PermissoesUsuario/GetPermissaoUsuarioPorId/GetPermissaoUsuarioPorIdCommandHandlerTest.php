<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 PHP DLX
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PainelDLX\Tests\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId;

use DLX\Infrastructure\EntityManagerX;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Repositories\PermissaoUsuarioRepositoryInterface;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;
use PainelDLX\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId\GetPermissaoUsuarioPorIdCommand;
use PainelDLX\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId\GetPermissaoUsuarioPorIdCommandHandler;

/**
 * Class GetPermissaoUsuarioPorIdCommandHandlerTest
 * @package PainelDLX\Tests\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId
 * @coversDefaultClass \PainelDLX\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId\GetPermissaoUsuarioPorIdCommandHandler
 */
class GetPermissaoUsuarioPorIdCommandHandlerTest extends PainelDLXTestCase
{
    /**
     * @return GetPermissaoUsuarioPorIdCommandHandler
     * @throws ORMException
     */
    public function test__construct(): GetPermissaoUsuarioPorIdCommandHandler
    {
        /** @var PermissaoUsuarioRepositoryInterface $permissao_usuario_repository */
        $permissao_usuario_repository = EntityManagerX::getRepository(PermissaoUsuario::class);
        $handler = new GetPermissaoUsuarioPorIdCommandHandler($permissao_usuario_repository);

        $this->assertInstanceOf(GetPermissaoUsuarioPorIdCommandHandler::class, $handler);

        return $handler;
    }

    /**
     * @param GetPermissaoUsuarioPorIdCommandHandler $handler
     * @covers ::handle
     * @depends test__construct
     */
    public function test_Handle_deve_retornar_null_quando_nao_encontrar_registro_bd(GetPermissaoUsuarioPorIdCommandHandler $handler)
    {
        $command = new GetPermissaoUsuarioPorIdCommand(0);
        $permissao_usuario = $handler->handle($command);

        $this->assertNull($permissao_usuario);
    }

    /**
     * @param GetPermissaoUsuarioPorIdCommandHandler $handler
     * @throws ORMException
     * @throws DBALException
     * @covers ::handle
     * @depends test__construct
     */
    public function test_Handle_deve_retornar_PermissaoUsuario_quando_encontrar_registro_bd(GetPermissaoUsuarioPorIdCommandHandler $handler)
    {
        $query = '
            select
                permissao_usuario_id
            from
                dlx_permissoes_usuario
            order by 
                rand()
            limit 1
        ';

        $sql = EntityManagerX::getInstance()->getConnection()->executeQuery($query);
        $id = $sql->fetchColumn();

        $command = new GetPermissaoUsuarioPorIdCommand($id);
        $permissao_usuario = $handler->handle($command);

        $this->assertInstanceOf(PermissaoUsuario::class, $permissao_usuario);
    }
}
