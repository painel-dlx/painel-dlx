<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 25/01/2019
 * Time: 17:31
 */

namespace PainelDLX\Presentation\Site\ErrosHttp\Controllers;


use League\Tactician\CommandBus;
use PainelDLX\Presentation\Site\Controllers\SiteController;
use Vilex\VileX;

class ErroHttp extends SiteController
{
    /**
     * ErroHttp constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     */
    public function __construct(VileX $view, CommandBus $commandBus)
    {
        parent::__construct($view, $commandBus);

        $this->view->setPaginaMestra('src/Presentation/Site/public/views/painel-dlx-master.phtml');
        $this->view->setViewRoot('src/Presentation/Site/public/views/erros-http');
    }
}