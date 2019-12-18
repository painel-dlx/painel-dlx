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
use DLX\Core\Exceptions\ArquivoConfiguracaoNaoEncontradoException;
use DLX\Core\Exceptions\ArquivoConfiguracaoNaoInformadoException;
use League\Route\Http\Exception\NotFoundException;
use PainelDLX\Application\Contracts\Router\ContainerInterface;
use PainelDLX\Application\Contracts\Router\RouterInterface;
use PainelDLX\Application\Routes\PainelDLXRouter;
use PainelDLX\Application\Services\Exceptions\AmbienteNaoInformadoException;
use PainelDLX\Presentation\Site\ErrosHttp\Controllers\ErroHttpController;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;

/**
 * Class PainelDLX
 * @package PainelDLX\Application\Services
 * @covers \PainelDLX\Application\Services\PainelDLX
 */
class PainelDLX
{
    /**
     * @var string
     */
    public static $dir = '';
    /**
     * @var self
     */
    private static $instance;
    /**
     * @var ServerRequestInterface
     */
    private $request;
    /**
     * @var ContainerInterface|null
     */
    private $container;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @param ServerRequestInterface $request
     * @return PainelDLX
     */
    public function setRequest(ServerRequestInterface $request): PainelDLX
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * @return ContainerInterface|null
     */
    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface|null $container
     * @return PainelDLX
     */
    public function setContainer(?ContainerInterface $container): PainelDLX
    {
        $this->container = $container;
        return $this;
    }

    /**
     * IniciarPainelDLX constructor.
     * @param ServerRequestInterface $server_request
     * @param RouterInterface $router
     * @param ContainerInterface|null $container
     */
    public function __construct(
        ServerRequestInterface $server_request,
        RouterInterface $router,
        ?ContainerInterface $container = null
    ) {
        $this->request = $server_request;
        $this->router = $router;
        $this->container = $container;
        $this->defineDirPainelDLX();
        $this->defineVersoes();

        self::$instance = $this;
    }

    /**
     * @return PainelDLX
     */
    public static function getInstance(): self
    {
        return self::$instance;
    }

    /**
     * @return PainelDLX
     * @throws AmbienteNaoInformadoException
     * @throws ArquivoConfiguracaoNaoEncontradoException
     * @throws ArquivoConfiguracaoNaoInformadoException
     */
    public function init(): PainelDLX
    {
        $this->carregarConfiguracao();

        $this->registrarDiretoriosInclusao(Configure::get('app', 'diretorios'));
        $this->registrarServiceProviders(Configure::get('app', 'service-providers'));
        $this->registrarRotas(Configure::get('app', 'rotas'));

        return $this;
    }

    /**
     * Executar a task desejada
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     */
    public function executar(): void
    {
        try {
            $response = $this->router->dispatch($this->request);
        } catch (NotFoundException $e) {
            /** @var ErroHttpController $controller */
            $controller = $this->getContainer()->get(ErroHttpController::class);
            $response = $controller->exibirPaginaErro($this->request->withQueryParams(['erro' => 404]));
        }

        echo $response->getBody();
    }

    /**
     * Redirecionar para outra ação.
     * @param ServerRequestInterface $request
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     * @deprecated é necessário? está sendo utilizado?
     */
    public function redirect(ServerRequestInterface $request)
    {
        $this->setRequest($request);
        $this->executar();
    }

    /**
     * @param string $diretorio
     * @return PainelDLX
     */
    public function adicionarDiretorioInclusao(string $diretorio): PainelDLX
    {
        $include_path = get_include_path();

        if (strpos($include_path, $diretorio) === false) {
            set_include_path($include_path . PATH_SEPARATOR . $diretorio . '/');
        }

        return $this;
    }

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     * @throws ArquivoConfiguracaoNaoInformadoException
     * @throws AmbienteNaoInformadoException
     */
    private function carregarConfiguracao(): void
    {
        if (!array_key_exists('ambiente', $this->request->getQueryParams())) {
            throw new AmbienteNaoInformadoException();
        }

        $ambiente = $this->request->getQueryParams()['ambiente'];
        Configure::init($ambiente, "config/{$ambiente}.php");
    }

    /**
     * Registrar rotas no RautereX
     * @param array $routers
     */
    private function registrarRotas(array $routers): void
    {
        foreach ($routers as $router) {
            /** @var PainelDLXRouter $router */
            $router = new $router($this->router);
            $router->registrar();
        }
    }

    /**
     * Registrar os service providers no container
     * @param array $service_providers
     */
    private function registrarServiceProviders(array $service_providers): void
    {
        if (!is_null($this->container)) {
            $this->container->registrarServicesProviders(... $service_providers);
        }
    }

    /**
     * Definir o path do Painel DLX
     */
    private function defineDirPainelDLX(): void
    {
        // Previnir que o diretório seja alterado caso alguém tenha setado ele manualmente
        if (empty(self::$dir)) {
            $base_dir = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR;
            $document_root = $this->request->getServerParams()['DOCUMENT_ROOT'];

            // Setar o path do PainelDLX
            self::$dir = trim(str_replace($document_root, '', $base_dir), DIRECTORY_SEPARATOR);

            // Garantir que os separadores de diretório estejam padronizados
            self::$dir = str_replace(DIRECTORY_SEPARATOR, '/', self::$dir);

            // Adicionar o path do PainelDLX no include_path
            $this->adicionarDiretorioInclusao($base_dir);
        }
    }

    /**
     * Registrar os diretórios de inclusão para facilitar os includes do sistema
     * @param array $diretorios
     */
    private function registrarDiretoriosInclusao(array $diretorios): void
    {
        foreach ($diretorios as $diretorio) {
            $this->adicionarDiretorioInclusao($diretorio);
        }
    }

    /**
     * Define as versões dos pacotes 'Painel DLX' instalados
     */
    private function defineVersoes(): void
    {
        // Todos os pacotes Painel-DLX, inclusive o atual
        $pacotes_painel_dlx = array_merge(
            glob('composer.json'),
            glob('vendor/painel-dlx/*/composer.json')
        );

        foreach ($pacotes_painel_dlx as $pacote) {
            $composer_json = json_decode(file_get_contents($pacote));

            if (!property_exists($composer_json, 'name')) {
                continue;
            }

            $nome_pacote = str_replace('painel-dlx/', '', $composer_json->name);
            $nome_constante = 'VERSAO_' . strtoupper(str_replace('-', '_', $nome_pacote));

            if (!defined($nome_constante)) {
                define($nome_constante, $composer_json->version);
            }
        }
    }
}