<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 10/01/2019
 * Time: 09:37
 */

namespace PainelDLX\Domain\Emails\Repositories;

use DLX\Domain\Repositories\EntityRepositoryInterface;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;

interface ConfigSmtpRepositoryInterface extends EntityRepositoryInterface
{
    /**
     * Verificar se existe outra configuração SMTP com o mesmo nome
     * @param ConfigSmtp $config_smtp
     * @return bool
     */
    public function existsOutroSmtpMesmoNome(ConfigSmtp $config_smtp): bool;
}