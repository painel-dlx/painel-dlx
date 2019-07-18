<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 05/02/2019
 * Time: 14:39
 */

namespace PainelDLX\Application\Middlewares;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SechianeX\Contracts\SessionInterface;

class DefinePaginaMestra implements MiddlewareInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * DefinePaginaMestra constructor.
     * @param ServerRequestInterface $request
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $qp = $request->getQueryParams();
        $pagina_mestra = array_key_exists('pg-mestra', $qp)
            ? filter_var($qp['pg-mestra'], FILTER_DEFAULT)
            : 'painel-dlx-master';
        $this->session->set('vilex:pagina-mestra', $pagina_mestra);

        return $handler->handle($request);
    }
}