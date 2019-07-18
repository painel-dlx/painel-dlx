<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/01/2019
 * Time: 18:18
 */

namespace PainelDLX\Application\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CriptografarSenhas implements MiddlewareInterface
{
    /**
     * @var string[]
     */
    private $senhas;

    /**
     * CriptografarSenhas constructor.
     * @param string ...$senhas
     */
    public function __construct(string ...$senhas)
    {
        $this->senhas = $senhas;
    }

    /**
     * Executar a criptografia das senhas
     */
    public function executar(): void
    {
        // TODO: Verirficar se há melhor alternativa para descoplar essa ServerRequestInterface e IniciarPainelDLX
        global $painel_dlx;
        $server_request = $painel_dlx->getRequest();

        $dados = $server_request->getMethod() === 'GET' ? $server_request->getQueryParams() : $server_request->getParsedBody();

        foreach ($this->senhas as $senha) {
            if (array_key_exists($senha, $dados)) {
                $dados[$senha] = $this->criptografar($dados[$senha]);
            }
        }

        $server_request = $server_request->getMethod() === 'GET'
            ? $server_request->withQueryParams($dados)
            : $server_request->withParsedBody($dados);

        $painel_dlx->setRequest($server_request);
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
        $dados = $request->getMethod() === 'GET' ? $request->getQueryParams() : $request->getParsedBody();

        foreach ($this->senhas as $senha) {
            if (array_key_exists($senha, $dados)) {
                $dados[$senha] = $this->criptografar($dados[$senha]);
            }
        }

        $request = $request->getMethod() === 'GET'
            ? $request->withQueryParams($dados)
            : $request->withParsedBody($dados);

        return $handler->handle($request);
    }

    /**
     * Crptografar a senha
     * @param string $senha
     * @return string
     */
    private function criptografar(string $senha): string
    {
        return md5(md5($senha));
    }
}