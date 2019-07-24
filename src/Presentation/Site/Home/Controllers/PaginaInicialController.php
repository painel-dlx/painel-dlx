<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 15/02/2019
 * Time: 09:13
 */

namespace PainelDLX\Presentation\Site\Home\Controllers;


use DLX\Core\Exceptions\UserException;
use League\Tactician\CommandBus;
use PainelDLX\UseCases\Home\GetListaWigets\GetListaWidgetsCommand;
use PainelDLX\UseCases\Home\GetListaWigets\GetListaWidgetsCommandHandler;
use PainelDLX\Presentation\Site\Common\Controllers\PainelDLXController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Vilex\VileX;

/**
 * Class PaginaInicialController
 * @package PainelDLX\Presentation\Site\Home\Controllers
 * @covers PaginaInicialControllerTest
 */
class PaginaInicialController extends PainelDLXController
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     * @throws ContextoInvalidoException
     */
    public function home(ServerRequestInterface $request): ResponseInterface
    {
        try {
            /* @see GetListaWidgetsCommandHandler  */
            $lista_widgets = $this->command_bus->handle(new GetListaWidgetsCommand());

            // Visões
            $this->view->addTemplate('home/index');

            // Parâmetros
            $this->view->setAtributo('lista-widgets', $lista_widgets);
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