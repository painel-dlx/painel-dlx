<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 10/01/2019
 * Time: 10:00
 */

namespace PainelDLX\Infra\ORM\Doctrine\Repositories;


use DLX\Infra\ORM\Doctrine\Repositories\EntityRepository;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;

class ConfigSmtpRepository extends EntityRepository implements ConfigSmtpRepositoryInterface
{

}