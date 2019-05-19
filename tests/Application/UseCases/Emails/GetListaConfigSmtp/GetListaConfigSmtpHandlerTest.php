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

namespace PainelDLX\Testes\Application\UseCases\Emails\GetListaConfigSmtp;

use DLX\Infra\EntityManagerX;
use Doctrine\Common\Collections\ArrayCollection;
use PainelDLX\UseCases\Emails\GetListaConfigSmtp\GetListaConfigSmtpCommand;
use PainelDLX\UseCases\Emails\GetListaConfigSmtp\GetListaConfigSmtpCommandHandler;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;

class GetListaConfigSmtpHandlerTest extends PainelDLXTestCase
{
    /** @var ConfigSmtpRepositoryInterface */
    private $usuario_repository;
    /** @var GetListaConfigSmtpCommandHandler */
    private $handler;

    protected function setUp()
    {
        parent::setUp();

        $this->usuario_repository = EntityManagerX::getRepository(ConfigSmtp::class);
        $this->handler = new GetListaConfigSmtpCommandHandler($this->usuario_repository);
    }

    public function test_Handle_sem_criteria()
    {
        $command = new GetListaConfigSmtpCommand();
        $lista_config_smtp_command = $this->handler->handle($command);
        $lista_config_smtp_repository = $this->usuario_repository->findBy(['deletado' => false]);

        $this->assertEquals($lista_config_smtp_repository, $lista_config_smtp_command);

        $lista_config_smtp_command_collection = new ArrayCollection($lista_config_smtp_command);

        // Não pode trazer registros que estão marcados como deletado
        $this->assertFalse($lista_config_smtp_command_collection->exists(function ($key, ConfigSmtp $config_smtp) {
            return $config_smtp->isDeletado();
        }));
    }

    public function test_Handle_com_criteria()
    {
        $criteria = ['nome' => 'Novo Usuário'];

        $command = new GetListaConfigSmtpCommand($criteria);
        $lista_config_smttp_command = $this->handler->handle($command);
        $lista_config_smtp_repository = array_filter(
            $this->usuario_repository->findByLike($criteria),
            function (ConfigSmtp $config_smtp) {
                return !$config_smtp->isDeletado();
            }
        );

        $this->assertEquals($lista_config_smtp_repository, $lista_config_smttp_command);

        $lista_usuarios_command_collection = new ArrayCollection($lista_config_smttp_command);

        // Não pode trazer registros que estão marcados como deletado
        $this->assertFalse($lista_usuarios_command_collection->exists(function ($key, ConfigSmtp $config_smtp) {
            return $config_smtp->isDeletado();
        }));
    }
}
