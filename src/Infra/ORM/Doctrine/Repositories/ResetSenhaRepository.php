<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 08:56
 */

namespace PainelDLX\Infra\ORM\Doctrine\Repositories;


use DLX\Infra\ORM\Doctrine\Repositories\EntityRepository;
use PainelDLX\Domain\Usuarios\Repositories\ResetSenhaRepositoryInterface;

class ResetSenhaRepository extends EntityRepository implements ResetSenhaRepositoryInterface
{

}