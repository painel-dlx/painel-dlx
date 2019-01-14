<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 09:34
 */

namespace PainelDLX\Application\UseCases\Usuarios\EnviarEmailResetSenha;


use PainelDLX\Application\Services\EnviarEmail;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PHPMailer\PHPMailer\PHPMailer;

class EnviarEmailResetSenhaHandler
{
    /**
     * @var PHPMailer
     */
    private $PHP_mailer;
    /**
     * @var ConfigSmtpRepositoryInterface
     */
    private $config_smtp_repository;

    /**
     * EnviarEmailResetSenhaHandler constructor.
     * @param PHPMailer $PHP_mailer
     * @param ConfigSmtpRepositoryInterface $config_smtp_repository
     */
    public function __construct(PHPMailer $PHP_mailer, ConfigSmtpRepositoryInterface $config_smtp_repository)
    {
        $this->PHP_mailer = $PHP_mailer;
        $this->config_smtp_repository = $config_smtp_repository;
    }

    /**
     * @param EnviarEmailResetSenhaCommand $command
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \PainelDLX\Application\Services\Exceptions\ErroAoEnviarEmailException
     */
    public function handle(EnviarEmailResetSenhaCommand $command)
    {
        $reset_senha = $command->getResetSenha();
        $config_smtp = $this->config_smtp_repository->findOneBy(['nome' => 'Gmail']);

        // TODO: Desacoplar a classe EnviarEmail
        $enviar_email = new EnviarEmail($this->PHP_mailer, $config_smtp, 'Painel DLX: Recuperação de senha', '');
        $enviar_email->enviarPara($reset_senha->getUsuario()->getEmail());
    }
}