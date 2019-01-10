<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 10/01/2019
 * Time: 12:12
 */

namespace PainelDLX\Application\UseCases\Emails\NovaConfigSmtp;


use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;

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
     */
    public function handle(NovaConfigSmtpCommand $command): ConfigSmtp
    {
        $this->config_smtp_repository->create($command->getConfigSmtp());
        return $command->getConfigSmtp();
    }
}