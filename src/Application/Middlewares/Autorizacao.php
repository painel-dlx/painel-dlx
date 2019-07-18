<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/12/2018
 * Time: 08:35
 */

namespace PainelDLX\Application\Middlewares;

use DLX\Core\Exceptions\UserException;
use Exception;
use PainelDLX\Application\Services\PainelDLX;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Presentation\Site\ErrosHttp\Controllers\ErroHttp;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SechianeX\Contracts\SessionInterface;
use SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException;
use SechianeX\Exceptions\SessionAdapterNaoEncontradoException;
use SechianeX\Factories\SessionFactory;

class Autorizacao implements MiddlewareInterface
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var string[]
     */
    private $permissoes;

    /**
     * Autorizacao constructor.
     * @param string ...$permissoes
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     */
    public function __construct(string ...$permissoes)
    {
        // TODO: Tentar desacoplar a sessão
        $this->session = SessionFactory::createPHPSession();
        $this->permissoes = $permissoes;
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     *
     * @throws Exception
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