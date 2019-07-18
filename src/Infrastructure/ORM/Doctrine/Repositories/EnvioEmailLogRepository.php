<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 10/01/2019
 * Time: 10:21
 */

namespace PainelDLX\Infrastructure\ORM\Doctrine\Repositories;


use DLX\Infra\ORM\Doctrine\Repositories\EntityRepository;
use PainelDLX\Domain\Emails\Repositories\EnvioEmailRepositoryInterface;

class EnvioEmailLogRepository extends EntityRepository implements EnvioEmailRepositoryInterface
{

}