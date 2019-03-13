<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 15/02/2019
 * Time: 08:51
 */

namespace PainelDLX\Infra\ORM\Doctrine\Repositories;


use DLX\Infra\ORM\Doctrine\Repositories\EntityRepository;
use PainelDLX\Domain\Home\Repositories\WidgetRepositoryInterface;

class WidgetRepository extends EntityRepository implements WidgetRepositoryInterface
{

}