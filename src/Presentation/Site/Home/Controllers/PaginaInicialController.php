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
use PainelDLX\Application\UseCases\Home\GetListaWigets\GetListaWidgetsCommand;
use PainelDLX\Application\UseCases\Home\GetListaWigets\GetListaWidgetsCommandHandler;
use PainelDLX\Presentation\Site\Controllers\SiteController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\VileX;

class PaginaInicialController extends SiteController
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * PaginaInicialController constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     * @param SessionInterface $session
     */
    public function __construct(VileX $view, CommandBus $commandBus, SessionInterface $session)
    {
        parent::__construct($view, $commandBus);

        $this->view->setPaginaMestra("src/Presentation/Site/public/views/paginas-mestras/{$session->get('vilex:pagina-mestra')}.phtml");
        $this->view->setViewRoot('src/Presentation/Site/public/views/home');

        $this->session = $session;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     */
    public function home(ServerRequestInterface $request): ResponseInterface
    {
        try {
            /**
             * @covers GetListaWidgetsCommandHandler
             */
            $lista_widgets = $this->command_bus->handle(new GetListaWidgetsCommand());

            # View
            $this->view->addTemplate('home', ['lista-widgets' => $lista_widgets]);
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