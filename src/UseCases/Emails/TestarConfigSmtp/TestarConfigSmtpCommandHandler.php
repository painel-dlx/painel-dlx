<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 31/01/2019
 * Time: 16:27
 */

namespace PainelDLX\UseCases\Emails\TestarConfigSmtp;


use PainelDLX\Infrastructure\Services\Email\EnviarEmail;
use PainelDLX\Application\Services\Exceptions\ErroAoEnviarEmailException;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class TestarConfigSmtpCommandHandler
 * @package PainelDLX\UseCases\Emails\TestarConfigSmtp
 * @covers TestarConfigSmtpCommandHandlerTest
 */
class TestarConfigSmtpCommandHandler
{
    /**
     * @var PHPMailer
     */
    private $PHP_mailer;

    /**
     * TestarConfigSmtpHandler constructor.
     * @param PHPMailer $PHP_mailer
     */
    public function __construct(PHPMailer $PHP_mailer)
    {
        $this->PHP_mailer = $PHP_mailer;
    }

    /**
     * @param TestarConfigSmtpCommand $command
     * @throws Exception
     * @throws ErroAoEnviarEmailException
     */
    public function handle(TestarConfigSmtpCommand $command)
    {
        // TODO: Desacoplar o serviço de envio de emails
        $enviar_email = new EnviarEmail($this->PHP_mailer, $command->getConfigSmtp(), 'Painel DLX: Teste de configuração de SMTP', '<p>Teste de configuração SMTP.</p>');
        $enviar_email->enviarPara($command->getEmail());
    }
}