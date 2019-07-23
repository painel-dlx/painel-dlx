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

namespace PainelDLX\UseCases\Emails\EditarConfigSmtp;


use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Exceptions\ConfigSmtpInvalidoException;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\Domain\Emails\Services\Validators\SalvarConfigSmtpValidator;

/**
 * Class EditarConfigSmtpCommandHandler
 * @package PainelDLX\UseCases\Emails\EditarConfigSmtp
 * @covers EditarConfigSmtpCommandHandlerTest
 */
class EditarConfigSmtpCommandHandler
{
    /**
     * @var ConfigSmtpRepositoryInterface
     */
    private $config_smtp_repository;
    /**
     * @var SalvarConfigSmtpValidator
     */
    private $validator;

    /**
     * EditarConfigSmtpCommandHandler constructor.
     * @param ConfigSmtpRepositoryInterface $config_smtp_repository
     * @param SalvarConfigSmtpValidator $validator
     */
    public function __construct(
        ConfigSmtpRepositoryInterface $config_smtp_repository,
        SalvarConfigSmtpValidator $validator
    ) {
        $this->config_smtp_repository = $config_smtp_repository;
        $this->validator = $validator;
    }

    /**
     * @param EditarConfigSmtpCommand $command
     * @return ConfigSmtp
     * @throws ConfigSmtpInvalidoException
     */
    public function handle(EditarConfigSmtpCommand $command): ConfigSmtp
    {
        $config_smtp  = $command->getConfigSmtp();

        $config_smtp->setNome($command->getNome());
        $config_smtp->setServidor($command->getServidor());
        $config_smtp->setPorta($command->getPorta());
        $config_smtp->setRequerAutent($command->isRequerAutent());
        $config_smtp->setConta($command->getConta());
        $config_smtp->setSenha($command->getSenha());
        $config_smtp->setCripto($command->getCripto());
        $config_smtp->setDeNome($command->getDeNome());
        $config_smtp->setResponderPara($command->getResponderPara());
        $config_smtp->setCorpoHtml($command->isCorpoHtml());

        $this->validator->validar($config_smtp);
        $this->config_smtp_repository->update($config_smtp);

        return $config_smtp;
    }
}