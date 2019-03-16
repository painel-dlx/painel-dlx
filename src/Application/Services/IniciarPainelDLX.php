<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 PHP DLX
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PainelDLX\Application\Services;


use DLX\Core\Configure;
use PainelDLX\Application\Routes\PainelDLXRouter;
use PainelDLX\Application\Services\Exceptions\AmbienteNaoInformadoException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use RautereX\RautereX;
use SechianeX\Factories\SessionFactory;

class IniciarPainelDLX
{
    /**
     * @var ServerRequestInterface
     */
    private $server_request;
    /**
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * @return ServerRequestInterface
     */
    public function getServerRequest(): ServerRequestInterface
    {
        return $this->server_request;
    }

    /**
     * @param ServerRequestInterface $server_request
     * @return IniciarPainelDLX
     */
    public function setServerRequest(ServerRequestInterface $server_request): IniciarPainelDLX
    {
        $this->server_request = $server_request;
        return $this;
    }

    /**
     * @param ContainerInterface|null $container
     * @return IniciarPainelDLX
     */
    public function setContainer(?ContainerInterface $container): IniciarPainelDLX
    {
        $this->container = $container;
        return $this;
    }

    /**
     * IniciarPainelDLX constructor.
     * @param string $ambiente
     * @param ServerRequestInterface $server_request
     * @param ContainerInterface|null $container
     */
    public function __construct(
        ServerRequestInterface $server_request,
        ?ContainerInterface $container = null
    ) {
        $this->server_request = $server_request;
        $this->container = $container;
    }

    /**
     * @throws \DLX\Core\Exceptions\ArquivoConfiguracaoNaoEncontradoException
     * @throws \DLX\Core\Exceptions\ArquivoConfiguracaoNaoInformadoException
     * @throws AmbienteNaoInformadoException
     */
    public function init(): IniciarPainelDLX
    {
        $this->carregarConfiguracao();
        return $this;
    }

    /**
     * @throws \RautereX\Exceptions\RotaNaoEncontradaException
     * @throws \ReflectionException
     */
    public function executar()
    {
        if (!is_null($this->container)) {
            $this->container->addServiceProvider(Configure::get('app', 'service-provider'));
        }

        // TODO: desacoplar a classe RautereX
        $router = new RautereX($this->container);
        $this->registrarRotas(Configure::get('app', 'rotas'), $router);

        $params = $this->server_request->getQueryParams();

        $response = $router->executarRota(
            $params['task'],
            $this->server_request,
            $this->server_request->getMethod()
        );
        echo $response->getBody();
    }

    /**
     * Redirecionar para outra ação.
     * @param ServerRequestInterface $request
     * @throws \RautereX\Exceptions\RotaNaoEncontradaException
     * @throws \ReflectionException
     */
    public function redirect(ServerRequestInterface $request)
    {
        $this->setServerRequest($request);
        $this->executar();
    }

    /**
     * @param string $diretorio
     * @return IniciarPainelDLX
     */
    public function adicionarDiretorioInclusao(string $diretorio): IniciarPainelDLX
    {
        $include_path = get_include_path();

        if (strpos($include_path, $diretorio) === false) {
            set_include_path($include_path . PATH_SEPARATOR . $diretorio . '/');
        }

        return $this;
    }

    /**
     * @throws \DLX\Core\Exceptions\ArquivoConfiguracaoNaoEncontradoException
     * @throws \DLX\Core\Exceptions\ArquivoConfiguracaoNaoInformadoException
     * @throws AmbienteNaoInformadoException
     */
    private function carregarConfiguracao(): void
    {
        if (!array_key_exists('ambiente', $this->server_request->getQueryParams())) {
            throw new AmbienteNaoInformadoException();
        }

        $ambiente = $this->server_request->getQueryParams()['ambiente'];
        Configure::init($ambiente, "config/{$ambiente}.php");
    }

    /**
     * Registrar rotas no RautereX
     * @param array $routers
     * @param RautereX $rautere_x
     */
    private function registrarRotas(array $routers, RautereX $rautere_x): void
    {
        foreach ($routers as $router) {
            /** @var PainelDLXRouter $router */
            $router = new $router($rautere_x, $this, SessionFactory::createPHPSession());
            $router->registrar();
        }
    }
}