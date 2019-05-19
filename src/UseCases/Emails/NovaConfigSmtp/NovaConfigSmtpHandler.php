<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 10/01/2019
 * Time: 12:12
 */

namespace PainelDLX\UseCases\Emails\NovaConfigSmtp;


use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Exceptions\AutentContaNaoInformadaException;
use PainelDLX\Domain\Emails\Exceptions\AutentSenhaNaoInformadaException;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\Domain\Emails\Services\Validators\SalvarConfigSmtpValidator;
use PainelDLX\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpCommand;

class NovaConfigSmtpHandler
{
    /**
     * @var ConfigSmtpRepositoryInterface
     */
    private $config_smtp_repository;

    /**
     * NovaConfigSmtpHandler constructor.
     * @param ConfigSmtpRepositoryInterface $config_smtp_repository
     */
    public function __construct(ConfigSmtpRepositoryInterface $config_smtp_repository)
    {
        $this->config_smtp_repository = $config_smtp_repository;
    }

    /**
     * @param NovaConfigSmtpCommand $command
     * @return ConfigSmtp
     * @throws AutentContaNaoInformadaException
     * @throws AutentSenhaNaoInformadaException
     */
    public function handle(NovaConfigSmtpCommand $command): ConfigSmtp
    {
        // Validar se a configuração SMTP pode ser salva
        (new SalvarConfigSmtpValidator($command->getConfigSmtp(), $this->config_smtp_repository))->validar();

        // Salvar a configuração SMTP
        $this->config_smtp_repository->create($command->getConfigSmtp());
        return $command->getConfigSmtp();
    }
}