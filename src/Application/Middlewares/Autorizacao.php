<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/12/2018
 * Time: 08:35
 */

namespace PainelDLX\Application\Middlewares;

use PainelDLX\Application\Services\PainelDLX;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Presentation\Site\ErrosHttp\Controllers\ErroHttp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;

class Autorizacao implements MiddlewareInterface
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var string[]
     */
    private $permissoes = [];

    /**
     * Autorizacao constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param string[] $permissoes
     * @return Autorizacao
     */
    public function setPermissoes(string ... $permissoes): self
    {
        $this->permissoes = $permissoes;
        return $this;
    }

    /**
     * Retornar uma nova instância configurada para validar as permissões informadas
     * @param string ...$permissoes
     * @return Autorizacao
     */
    public function necessitaPermissoes(string ... $permissoes): self
    {
        $this_clone = new self($this->session);
        return $this_clone->setPermissoes(... $permissoes);
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Usuario $usuario */
        $usuario = $this->session->get('usuario-logado');

        foreach ($this->permissoes as $permissao) {
            if (!$usuario->hasPermissao($permissao)) {
                /** @var ErroHttp $login_controller */
                $login_controller = PainelDLX::getInstance()->getContainer()->get(ErroHttp::class);
                return $login_controller->exibirPaginaErro($request->withQueryParams(['erro' => 403]));
            }
        }

        return $handler->handle($request);
    }
}