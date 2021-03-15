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

namespace PainelDLX\Presentation\Web\Usuarios\Controllers;


use DLX\Core\Exceptions\UserException;
use League\Tactician\CommandBus;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioInvalidoException;
use PainelDLX\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommand;
use PainelDLX\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommandHandler;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\ValueObjects\SenhaUsuario;
use PainelDLX\Presentation\Web\Common\Controllers\PainelDLXController;
use PainelDLX\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\TemplateInvalidoException;
use Vilex\VileX;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class MinhaContaController extends PainelDLXController
{
    /**
     * @var Usuario
     */
    private $usuario_logado;

    /**
     * MinhaContaController constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     * @param SessionInterface $session
     * @throws TemplateInvalidoException
     */
    public function __construct(
        VileX $view,
        CommandBus $commandBus,
        SessionInterface $session
    ) {
        parent::__construct($view, $commandBus, $session);
        $this->usuario_logado = $this->session->get('usuario-logado');
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     */
    public function meusDados(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // Atributos
            $this->view->setAtributo('titulo-pagina', $this->usuario_logado->getNome());
            $this->view->setAtributo('usuario', $this->usuario_logado);

            // Visão
            $this->view->addTemplate('usuarios/det_usuario', [
                'usuario' => $this->usuario_logado,
                'is-usuario-logado' => true
            ]);
        } catch (UserException $e) {
            $this->view->addTemplate('common/mensagem_usuario', [
                'mensagem' => [
                    'tipo' => 'erro',
                    'texto' => $e->getMessage()
                ]
            ]);
        }

        return $this->view->render();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     */
    public function formAlterarMinhaSenha(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Alterar minha senha');

            // Views
            $this->view->addTemplate('usuarios/form_alterar_senha', [
                'usuario' => $this->usuario_logado
            ]);

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js', false, VERSAO_PAINEL_DLX);
        } catch (UserException $e) {
            $this->view->addTemplate('common/mensagem_usuario', [
                'mensagem' => [
                    'tipo' => 'erro',
                    'texto' => $e->getMessage()
                ]
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
        $post = filter_var_array($request->getParsedBody(), [
            'senha_atual' => FILTER_DEFAULT,
            'senha_nova' => FILTER_DEFAULT,
            'senha_confirm' => FILTER_DEFAULT
        ]);

        /**
         * @var string $senha_atual
         * @var string $senha_nova
         * @var string $senha_confirm
         */
        extract($post); unset($post);

        try {
            /** @var Usuario $usuario */
            /* @see GetUsuarioPeloIdCommandHandler */
            // $usuario = $this->command_bus->handle(new GetUsuarioPeloIdCommand($this->usuario_logado->getUsuarioId()));

            $senha_usuario = new SenhaUsuario($senha_nova, $senha_confirm, $senha_atual);

            /* @see AlterarSenhaUsuarioCommandHandler */
            $this->command_bus->handle(new AlterarSenhaUsuarioCommand($this->usuario_logado, $senha_usuario));

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Senha alterada com sucesso!';
        } catch (UsuarioInvalidoException | UserException $e) {
            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }

    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     */
    public function resumoInformacoes(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // Visão
            $this->view->addTemplate('usuarios/resumo_infos', [
                'usuario' => $this->usuario_logado
            ]);

            // Parâmetros
            $this->view->setAtributo('usuario', $this->usuario_logado);
        } catch (UserException $e) {
            $this->view->addTemplate('common/mensagem_usuario', [
                'mensagem' => [
                    'tipo' => 'erro',
                    'texto' => $e->getMessage()
                ]
            ]);
        }

        return $this->view->render();
    }
}