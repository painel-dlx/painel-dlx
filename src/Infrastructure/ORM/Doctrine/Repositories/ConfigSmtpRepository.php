<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 10/01/2019
 * Time: 10:00
 */

namespace PainelDLX\Infrastructure\ORM\Doctrine\Repositories;


use DLX\Infrastructure\ORM\Doctrine\Repositories\EntityRepository;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;

class ConfigSmtpRepository extends EntityRepository implements ConfigSmtpRepositoryInterface
{

    /**
     * Verificar se existe outra configuração SMTP com o mesmo nome
     * @param ConfigSmtp $config_smtp
     * @return bool
     */
    public function existsOutroSmtpMesmoNome(ConfigSmtp $config_smtp): bool
    {
        // TODO: Implement existsOutroSmtpMesmoNome() method.
        return false;
    }
}