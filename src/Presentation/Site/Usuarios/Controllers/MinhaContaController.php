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

namespace PainelDLX\Presentation\Site\Usuarios\Controllers;


use DLX\Core\Exceptions\UserException;
use League\Tactician\CommandBus;
use PainelDLX\Application\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommand;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\ValueObjects\SenhaUsuario;
use PainelDLX\Presentation\Site\Controllers\SiteController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\VileX;
use Zend\Diactoros\Response\JsonResponse;

class MinhaContaController extends SiteController
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var Usuario
     */
    private $usuario_logado;

    /**
     * MinhaContaController constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     * @param SessionInterface $session
     */
    public function __construct(
        VileX $view,
        CommandBus $commandBus,
        SessionInterface $session
    ) {
        parent::__construct($view, $commandBus);

        $this->view->setPaginaMestra('src/Presentation/Site/public/views/painel-dlx-master.phtml');
        $this->view->setViewRoot('src/Presentation/Site/public/views/usuarios');
        $this->session = $session;
        $this->usuario_logado = $this->session->get('usuario-logado');
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     */
    public function meusDados(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // Atributos
            $this->view->setAtributo('titulo-pagina', $this->usuario_logado->getNome());

            // Visão
            $this->view->addTemplate('det_usuario', [
                'usuario' => $this->usuario_logado,
                'is-usuario-logado' => true
            ]);
        } catch (UserException $e) {
            $this->view->addTemplate('../mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'texto' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     */
    public function formAlterarMinhaSenha(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Alterar minha senha');
            $this->view->setAtributo('usuario', $this->usuario_logado);

            // Views
            $this->view->addTemplate('../form_alterar_senha');

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js');
        } catch (UserException $e) {
            $this->view->addTemplate('../mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'texto' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function alterarMinhaSenha(ServerRequestInterface $request): ResponseInterface
    {
        $post = filter_var_array(
            $request->getParsedBody(),
            [
                'senha_atual' => FILTER_DEFAULT,
                'senha_nova' => FILTER_DEFAULT,
                'senha_confirm' => FILTER_DEFAULT
            ]
        );

        /**
         * @var string $senha_atual
         * @var string $senha_nova
         * @var string $senha_confirm
         */
        extract($post); unset($post);

        try {
            $senha_usuario = new SenhaUsuario($senha_nova, $senha_confirm, $senha_atual);

            /** @covers AlterarSenhaUsuarioHandler */
            $this->command_bus->handle(new AlterarSenhaUsuarioCommand($this->usuario_logado, $senha_usuario));

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Senha alterada com sucesso!';
        } catch (UserException $e) {
            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }
}