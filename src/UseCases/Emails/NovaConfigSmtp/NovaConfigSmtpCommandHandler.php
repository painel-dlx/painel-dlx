<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 10/01/2019
 * Time: 12:12
 */

namespace PainelDLX\UseCases\Emails\NovaConfigSmtp;


use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Exceptions\ConfigSmtpInvalidoException;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\Domain\Emails\Services\Validators\SalvarConfigSmtpValidator;

/**
 * Class NovaConfigSmtpHandler
 * @package PainelDLX\UseCases\Emails\NovaConfigSmtp
 * @covers NovaConfigSmtpHandlerTest
 */
class NovaConfigSmtpCommandHandler
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
     * NovaConfigSmtpHandler constructor.
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
     * @param NovaConfigSmtpCommand $command
     * @return ConfigSmtp
     * @throws ConfigSmtpInvalidoException
     */
    public function handle(NovaConfigSmtpCommand $command): ConfigSmtp
    {
        $config_smtp = new ConfigSmtp($command->getServidor(), $command->getPorta());
        $config_smtp->setNome($command->getNome());
        $config_smtp->setRequerAutent($command->isRequerAutent());
        $config_smtp->setConta($command->getConta());
        $config_smtp->setSenha($command->getSenha());
        $config_smtp->setCripto($command->getCripto());
        $config_smtp->setDeNome($command->getDeNome());
        $config_smtp->setResponderPara($command->getResponderPara());
        $config_smtp->setCorpoHtml($command->isCorpoHtml());

        // Validar se a configuração SMTP pode ser salva
        $this->validator->validar($config_smtp);

        // Salvar a configuração SMTP
        $this->config_smtp_repository->create($config_smtp);
        return $config_smtp;
    }
}