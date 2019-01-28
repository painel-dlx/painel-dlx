<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 25/01/2019
 * Time: 17:31
 */

namespace PainelDLX\Presentation\Site\ErrosHttp\Controllers;


use DLX\Core\Exceptions\UserException;
use League\Tactician\CommandBus;
use PainelDLX\Presentation\Site\Controllers\SiteController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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

        $this->view->setPaginaMestra('src/Presentation/Site/public/views/paginas-mestras/painel-dlx-master.phtml');
        $this->view->setViewRoot('src/Presentation/Site/public/views/erros-http');
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     */
    public function exibirPaginaErro(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array($request->getQueryParams(), [
            'erro' => FILTER_VALIDATE_INT
        ]);

        try {
            // Visão
            $this->view->addTemplate($get['erro']);
        } catch (UserException $e) {
            $this->view->addTemplate('../mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'texto' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }
}