<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 10/01/2019
 * Time: 12:10
 */

namespace PainelDLX\UseCases\Emails\NovaConfigSmtp;


use PainelDLX\Domain\Emails\Entities\ConfigSmtp;

class NovaConfigSmtpCommand
{
    /**
     * @var ConfigSmtp
     */
    private $configSmtp;

    /**
     * @return ConfigSmtp
     */
    public function getConfigSmtp(): ConfigSmtp
    {
        return $this->configSmtp;
    }

    /**
     * NovaConfigSmtpCommand constructor.
     * @param ConfigSmtp $configSmtp
     */
    public function __construct(ConfigSmtp $configSmtp)
    {
        $this->configSmtp = $configSmtp;
    }
}