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

namespace PainelDLX\Presentation\Site\PermissoesUsuario\Controllers;


use DLX\Core\Exceptions\UserException;
use League\Tactician\CommandBus;
use PainelDLX\Application\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario\CadastrarPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario\CadastrarPermissaoUsuarioHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\ExcluirPermissaoUsuario\ExcluirPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\ExcluirPermissaoUsuario\ExcluirPermissaoUsuarioHandler;
use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Repositories\PermissaoUsuarioRepositoryInterface;
use PainelDLX\Presentation\Site\Controllers\SiteController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\VileX;
use Zend\Diactoros\Response\JsonResponse;

class CadastroPermissaoController extends SiteController
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * CadastroPermissaoController constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     * @param PermissaoUsuarioRepositoryInterface $permissao_usuario_repository
     * @param SessionInterface $session
     */
    public function __construct(
        VileX $view,
        CommandBus $commandBus,
        PermissaoUsuarioRepositoryInterface $permissao_usuario_repository,
        SessionInterface $session
    ) {
        parent::__construct($view, $commandBus);

        $this->view->setPaginaMestra("src/Presentation/Site/public/views/paginas-mestras/{$session->get('vilex:pagina-mestra')}.phtml");
        $this->view->setViewRoot('src/Presentation/Site/public/views/permissoes');

        $this->repository = $permissao_usuario_repository;
        $this->session = $session;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     */
    public function listaPermissoesUsuarios(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $lista_permissoes = $this->repository->findBy($request->getParsedBody());

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Permissões');
            $this->view->setAtributo('lista_permissoes', $lista_permissoes);

            // Views
            $this->view->addTemplate('lista_permissoes');
        } catch (UserException $e) {
            $this->view->addTemplate('mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'mensagem' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     */
    public function formNovaPermissaoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Criar nova permissão');

            // Views
            $this->view->addTemplate('form_nova_permissao');

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js');
        } catch (UserException $e) {
            $this->view->addTemplate('mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'mensagem' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function criarNovaPermissao(ServerRequestInterface $request): ResponseInterface
    {
        $post = filter_var_array(
            $request->getParsedBody(),
            [
                'alias' => FILTER_SANITIZE_STRING,
                'descricao' => FILTER_SANITIZE_STRING
            ]
        );

        /**
         * @var string $alias
         * @var string $descricao
         */
        extract($post); unset($post);

        try {
            /**
             * @var PermissaoUsuario $permissao_usuario
             * @covers CadastrarPermissaoUsuarioHandler
             */
            $permissao_usuario = $this->command_bus->handle(new CadastrarPermissaoUsuarioCommand($alias, $descricao));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Permissão criada com sucesso!';
            $msg['permissao-usuario'] = $permissao_usuario->getPermissaoUsuarioId();
        } catch (UserException $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     */
    public function formEditarPermissaoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array(
            $request->getQueryParams(),
            ['permissao_usuario_id' => FILTER_VALIDATE_INT]
        );

        /**
         * @var int $permissao_usuario_id
         */
        extract($get); unset($get);

        try {
            /** @var PermissaoUsuario $permissao_usuario */
            $permissao_usuario = $this->repository->find($permissao_usuario_id);

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Editar permissão');

            // Views
            $this->view->addTemplate('form_editar_permissao', [
                'permissao-usuario' => $permissao_usuario
            ]);

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js');
        } catch (UserException $e) {
            $this->view->addTemplate('mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'mensagem' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function alterarPermissaoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        $post = filter_var_array(
            $request->getParsedBody(),
            [
                'permissao_usuario_id' => FILTER_VALIDATE_INT,
                'descricao' => FILTER_SANITIZE_STRING
            ]
        );

        /**
         * @var int $permissao_usuario_id
         * @var string $descricao
         */
        extract($post); unset($post);

        try {
            /**
             * @var PermissaoUsuario $permissao_usuario
             * @covers EditarPermissaoUsuarioHandler
             */
            $permissao_usuario = $this->command_bus->handle(new EditarPermissaoUsuarioCommand($permissao_usuario_id, $descricao));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Permissão alterada com sucesso!';
            $msg['permissao-usuario'] = $permissao_usuario->getPermissaoUsuarioId();
        } catch (UserException $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     */
    public function detalhePermissaoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array(
            $request->getQueryParams(),
            ['permissao_usuario_id' => FILTER_VALIDATE_INT]
        );

        /**
         * @var int $permissao_usuario_id
         */
        extract($get); unset($get);

        try {
            /** @var PermissaoUsuario|null $permissao_usuario */
            $permissao_usuario = $this->repository->find($permissao_usuario_id);

            if (!$permissao_usuario instanceof PermissaoUsuario) {
                throw new UserException('Permissão de usuário não encontrada.');
            }

            // Atributos
            $this->view->setAtributo('titulo-pagina', "Permissão: {$permissao_usuario->getDescricao()}");

            // Views
            $this->view->addTemplate('det_permissao', [
                'permissao-usuario' => $permissao_usuario
            ]);

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js');
        } catch (UserException $e) {
            $this->view->addTemplate('mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'mensagem' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function excluirPermissaoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        $post = filter_var_array(
            $request->getParsedBody(),
            ['permissao_usuario_id' => FILTER_VALIDATE_INT]
        );

        /**
         * @var int $permissao_usuario_id
         */
        extract($post); unset($post);

        try {
            /**
             * @covers ExcluirPermissaoUsuarioHandler
             */
            $this->command_bus->handle(new ExcluirPermissaoUsuarioCommand($permissao_usuario_id));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Permissão excluída com sucesso!';
        } catch (UserException $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }
}