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

namespace PainelDLX\Application\Services;


use PainelDLX\Application\Services\Exceptions\ErroAoEnviarEmailException;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PHPMailer\PHPMailer\PHPMailer;

class EnviarEmail
{
    /**
     * @var PHPMailer
     */
    private $php_mailer;
    /**
     * @var ConfigSmtp
     */
    private $config_smtp;
    /**
     * @var string
     */
    private $assunto;
    /**
     * @var string
     */
    private $corpo;

    /**
     * @return PHPMailer
     */
    public function getPhpMailer(): PHPMailer
    {
        return $this->php_mailer;
    }

    /**
     * @return ConfigSmtp
     */
    public function getConfigSmtp(): ConfigSmtp
    {
        return $this->config_smtp;
    }

    /**
     * @return string
     */
    public function getAssunto(): string
    {
        return $this->assunto;
    }

    /**
     * @return string
     */
    public function getCorpo(): string
    {
        return $this->corpo;
    }

    /**
     * EnviarEmail constructor.
     * @param PHPMailer $php_mailer
     * @param string $assunto
     * @param string $corpo
     */
    public function __construct(PHPMailer $php_mailer, ConfigSmtp $config_smtp, string $assunto, string $corpo)
    {
        $this->php_mailer = $php_mailer;
        $this->config_smtp = $config_smtp;
        $this->assunto = $assunto;
        $this->corpo = $corpo;
    }

    /**
     * @param string $para
     * @param string|null $cc
     * @param string|null $cco
     * @return bool
     * @throws ErroAoEnviarEmailException
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function enviarPara(string $para, ?string $cc = null, ?string $cco = null): bool
    {
        // Configurações do PHPMailer
        $this->php_mailer->setFrom($this->config_smtp->getConta(), $this->config_smtp->getDeNome());
        $this->php_mailer->isSMTP();

        // Configurações do servidor
        $this->php_mailer->Host = $this->config_smtp->getServidor();
        $this->php_mailer->Port = $this->config_smtp->getPorta();

        if ($this->config_smtp->isRequerAutent()) {
            $this->php_mailer->SMTPAuth = true;
            $this->php_mailer->Username = $this->config_smtp->getConta();
            $this->php_mailer->Password = $this->config_smtp->getSenha();

            if (!empty($this->config_smtp->getCripto())) {
                $this->php_mailer->SMTPSecure = $this->config_smtp->getCripto();
            }
        }

        // Configurações do email
        $this->php_mailer->Subject = utf8_decode($this->assunto);
        $this->php_mailer->Body = $this->corpo;
        $this->php_mailer->isHTML($this->config_smtp->isCorpoHtml());

        $this->php_mailer->SMTPDebug = 0;

        if (!empty($this->config_smtp->getResponderPara())) {
            $this->php_mailer->addReplyTo($this->config_smtp->getResponderPara());
        }

        $emails_para = explode(';', $para);
        foreach ($emails_para as $email) {
            $this->php_mailer->addAddress($email);
        }

        if (!empty($cc)) {
            $emails_cc = explode(';', $cc);
            foreach ($emails_cc as $email) {
                $this->php_mailer->addCC($email);
            }
        }

        if (!empty($cco)) {
            $emails_cco = explode(';', $cco);
            foreach ($emails_cco as $email) {
                $this->php_mailer->addBCC($email);
            }
        }

        $envio = $this->php_mailer->send();

        if (!$envio) {
            throw new ErroAoEnviarEmailException($para, $this->php_mailer->ErrorInfo);
        }

        return true;
    }
}