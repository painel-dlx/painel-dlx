<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 31/01/2019
 * Time: 16:53
 */

namespace PainelDLX\Testes\Application\UseCases\Emails\TestarConfigSmtp;

use PainelDLX\Application\Services\Exceptions\ErroAoEnviarEmailException;
use PainelDLX\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpCommand;
use PainelDLX\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpCommandHandler;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;

/**
 * Class TestarConfigSmtpCommandHandlerTest
 * @package PainelDLX\Testes\Application\UseCases\Emails\TestarConfigSmtp
 * @coversDefaultClass \PainelDLX\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpCommandHandler
 */
class TestarConfigSmtpCommandHandlerTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ErroAoEnviarEmailException
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
        (new TestarConfigSmtpCommandHandler(new PHPMailer()))->handle($command);
    }
}
