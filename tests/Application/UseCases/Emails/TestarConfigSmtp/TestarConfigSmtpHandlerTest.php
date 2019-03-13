<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 31/01/2019
 * Time: 16:53
 */

namespace PainelDLX\Testes\Application\UseCases\Emails\TestarConfigSmtp;

use PainelDLX\Application\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpCommand;
use PainelDLX\Application\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpHandler;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;

class TestarConfigSmtpHandlerTest extends TestCase
{
    /**
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \PainelDLX\Application\Services\Exceptions\ErroAoEnviarEmailException
     */
    public function test_Handle_config_smtp_gmail()
    {
        $this->expectNotToPerformAssertions();

        $config_smtp = new ConfigSmtp('smtp.gmail.com', 587);
        $config_smtp
            ->setRequerAutent(true)
            ->setConta('dlepera88.emails@gmail.com')
            ->setSenha('oxswveitoainkmbu')
            ->setCripto('tls')
            ->setCorpoHtml(true);
        
        $command = new TestarConfigSmtpCommand($config_smtp, 'dlepera88@gmail.com');
        (new TestarConfigSmtpHandler(new PHPMailer()))->handle($command);
    }
}
