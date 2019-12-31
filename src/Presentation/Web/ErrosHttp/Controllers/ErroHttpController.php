<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 25/01/2019
 * Time: 17:31
 */

namespace PainelDLX\Presentation\Web\ErrosHttp\Controllers;


use DLX\Core\Exceptions\UserException;
use PainelDLX\Presentation\Web\Common\Controllers\PainelDLXController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\TemplateInvalidoException;

class ErroHttpController extends PainelDLXController
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     */
    public function exibirPaginaErro(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array($request->getQueryParams(), [
            'erro' => FILTER_VALIDATE_INT
        ]);

        try {
            // Atributos
            $this->view->setAtributo('titulo-pagina', "Ops! Deu erro {$get['erro']}");

            // Visão
            $this->view->addTemplate("erros-http/{$get['erro']}");
        } catch (UserException $e) {
            $this->view->addTemplate('common/mensagem_usuario', [
                'mensagem' => [
                    'tipo' => 'erro',
                    'texto' => $e->getMessage()
                ]
            ]);
        }

        return $this->view->render();
    }
}