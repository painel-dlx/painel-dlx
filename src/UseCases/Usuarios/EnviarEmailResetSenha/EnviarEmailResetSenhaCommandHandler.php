<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 09:34
 */

namespace PainelDLX\UseCases\Usuarios\EnviarEmailResetSenha;


use PainelDLX\Infrastructure\Services\Email\EnviarEmail;
use PainelDLX\Application\Services\Exceptions\ErroAoEnviarEmailException;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\UseCases\Usuarios\EnviarEmailResetSenha\EnviarEmailResetSenhaCommand;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EnviarEmailResetSenhaCommandHandler
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
     * EnviarEmailResetSenhaCommandHandler constructor.
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
     * @throws Exception
     * @throws ErroAoEnviarEmailException
     */
    public function handle(EnviarEmailResetSenhaCommand $command)
    {
        $reset_senha = $command->getResetSenha();
        $config_smtp = $this->config_smtp_repository->findOneBy(['nome' => 'SMTP Gmail']);

        $corpo = '
            <p>Olá ' . $reset_senha->getUsuario()->getNome() . '!</p>
            <p>Você solicitou a recuperação da sua senha no sistema <strong>Painel DLX</strong>.</p>
            <p>
                Para recuperar a sua senha, clique no link abaixo.<br>
            
                <a href="http://painel-dlx.localhost/painel-dlx/recuperar-minha-senha?hash=' . $reset_senha->getHash() . '">
                    Recuperar senha
                </a>
            </p>
            
            <p>Obs: Se você não fez essa solicitação, desconsidere esse email e <b>NÃO CLIQUE</b> no link a cima!</p>';

        // TODO: Desacoplar a classe EnviarEmail
        $enviar_email = new EnviarEmail($this->PHP_mailer, $config_smtp, 'Painel DLX: Recuperação de senha', $corpo);
        $enviar_email->enviarPara($reset_senha->getUsuario()->getEmail());
    }
}