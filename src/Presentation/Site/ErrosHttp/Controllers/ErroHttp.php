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
use PainelDLX\Presentation\Site\Common\Controllers\PainelDLXController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Vilex\VileX;

class ErroHttp extends PainelDLXController
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ViewNaoEncontradaException
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     */
    public function exibirPaginaErro(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array($request->getQueryParams(), [
            'erro' => FILTER_VALIDATE_INT
        ]);

        try {
            // Visão
            $this->view->addTemplate("erros-http/{$get['erro']}");
        } catch (UserException $e) {
            $this->view->addTemplate('common/mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'texto' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }
}