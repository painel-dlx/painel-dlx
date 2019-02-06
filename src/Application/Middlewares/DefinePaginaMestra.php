<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 05/02/2019
 * Time: 14:39
 */

namespace PainelDLX\Application\Middlewares;


use PainelDLX\Application\Contracts\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;

class DefinePaginaMestra implements MiddlewareInterface
{
    /**
     * @var ServerRequestInterface
     */
    private $request;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * DefinePaginaMestra constructor.
     * @param ServerRequestInterface $request
     * @param SessionInterface $session
     */
    public function __construct(ServerRequestInterface $request, SessionInterface $session)
    {
        $this->request = $request;
        $this->session = $session;
    }

    public function executar()
    {
        $qp = $this->request->getQueryParams();
        $pagina_mestra = array_key_exists('pg-mestra', $qp)
            ? filter_var($qp['pg-mestra'], FILTER_DEFAULT)
            : 'painel-dlx-master';
        $this->session->set('vilex:pagina-mestra', $pagina_mestra);
    }
}