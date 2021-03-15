<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 15/02/2019
 * Time: 09:13
 */

namespace PainelDLX\Presentation\Web\Home\Controllers;


use DLX\Core\Exceptions\UserException;
use PainelDLX\UseCases\Home\GetListaWigets\GetListaWidgetsCommand;
use PainelDLX\UseCases\Home\GetListaWigets\GetListaWidgetsCommandHandler;
use PainelDLX\Presentation\Web\Common\Controllers\PainelDLXController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\TemplateInvalidoException;

/**
 * Class PaginaInicialController
 * @package PainelDLX\Presentation\Web\Home\Controllers
 * @covers PaginaInicialControllerTest
 */
class PaginaInicialController extends PainelDLXController
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     */
    public function home(ServerRequestInterface $request): ResponseInterface
    {
        try {
            /* @see GetListaWidgetsCommandHandler  */
            $lista_widgets = $this->command_bus->handle(new GetListaWidgetsCommand());

            // Visões
            $this->view->addTemplate('home/index', [
                'lista-widgets' => $lista_widgets
            ]);

            // Parâmetros
            $this->view->setAtributo('titulo-pagina', 'Home');
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