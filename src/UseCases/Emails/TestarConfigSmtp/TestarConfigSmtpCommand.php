<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 31/01/2019
 * Time: 16:22
 */

namespace PainelDLX\UseCases\Emails\TestarConfigSmtp;


use PainelDLX\Domain\Emails\Entities\ConfigSmtp;

class TestarConfigSmtpCommand
{
    /**
     * @var ConfigSmtp
     */
    private $config_smtp;
    /**
     * @var string
     */
    private $email;

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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * TestarConfigSmtpCommand constructor.
     * @param ConfigSmtp $config_smtp
     * @param string $email
     */
    public function __construct(ConfigSmtp $config_smtp, string $email)
    {
        $this->config_smtp = $config_smtp;
        $this->email = $email;
    }
}