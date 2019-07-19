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


use DLX\Core\Configure;
use DLX\Core\Exceptions\UserException;
use League\Tactician\CommandBus;
use PainelDLX\UseCases\ListaRegistros\ConverterFiltro2Criteria\ConverterFiltro2CriteriaCommand;
use PainelDLX\UseCases\ListaRegistros\ConverterFiltro2Criteria\ConverterFiltro2CriteriaCommandHandler;
use PainelDLX\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario\CadastrarPermissaoUsuarioCommand;
use PainelDLX\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario\CadastrarPermissaoUsuarioCommandHandler;
use PainelDLX\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioCommand;
use PainelDLX\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioCommandHandler;
use PainelDLX\UseCases\PermissoesUsuario\ExcluirPermissaoUsuario\ExcluirPermissaoUsuarioCommand;
use PainelDLX\UseCases\PermissoesUsuario\ExcluirPermissaoUsuario\ExcluirPermissaoUsuarioCommandHandler;
use PainelDLX\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioCommand;
use PainelDLX\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioCommandHandler;
use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Repositories\PermissaoUsuarioRepositoryInterface;
use PainelDLX\Presentation\Site\Common\Controllers\PainelDLXController;
use PainelDLX\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId\GetPermissaoUsuarioPorIdCommand;
use PainelDLX\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId\GetPermissaoUsuarioPorIdCommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Vilex\VileX;
use Zend\Diactoros\Response\JsonResponse;

class CadastroPermissaoController extends PainelDLXController
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     */
    public function listaPermissoesUsuarios(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array($request->getQueryParams(), [
            'campos' => ['filter' => FILTER_SANITIZE_STRING, 'flags' => FILTER_REQUIRE_ARRAY],
            'busca' => FILTER_DEFAULT,
            'pg' => FILTER_VALIDATE_INT,
            'qtde' => FILTER_VALIDATE_INT,
            'offset' => FILTER_VALIDATE_INT
        ]);

        try {
            /** @var array $criteria */
            /* @see ConverterFiltro2CriteriaCommandHandler */
            $criteria = $this->command_bus->handle(new ConverterFiltro2CriteriaCommand($get['campos'], $get['busca']));

            /** @var array $lista_permissoes */
            /* @see GetListaPermissaoUsuarioCommandHandler */
            $lista_permissoes = $this->command_bus->handle(new GetListaPermissaoUsuarioCommand(
                $criteria,
                [],
                $get['qtde'],
                $get['offset']
            ));

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Permissões');
            $this->view->setAtributo('lista-permissoes', $lista_permissoes);
            $this->view->setAtributo('filtro', $get);
            $this->view->setAtributo('pagina-atual', $get['pg']);

            // Views
            $this->view->addTemplate('permissoes/lista_permissoes');
            $this->view->addTemplate('common/paginacao');
        } catch (UserException $e) {
            $this->view->addTemplate('common/mensagem_usuario');
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
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     * @throws ContextoInvalidoException
     */
    public function formNovaPermissaoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Criar nova permissão');

            // Views
            $this->view->addTemplate('permissoes/form_nova_permissao');

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js', false, Configure::get('app', 'versao'));
        } catch (UserException $e) {
            $this->view->addTemplate('common/mensagem_usuario');
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
        $post = filter_var_array($request->getParsedBody(), [
            'alias' => FILTER_SANITIZE_STRING,
            'descricao' => FILTER_SANITIZE_STRING
        ]);

        /**
         * @var string $alias
         * @var string $descricao
         */
        extract($post); unset($post);

        try {
            /** @var PermissaoUsuario $permissao_usuario */
            /* @see CadastrarPermissaoUsuarioCommandHandler */
            $permissao_usuario = $this->command_bus->handle(new CadastrarPermissaoUsuarioCommand($alias, $descricao));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Permissão criada com sucesso!';
            $msg['permissao_usuario_id'] = $permissao_usuario->getId();
        } catch (UserException $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     */
    public function formEditarPermissaoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array($request->getQueryParams(), [
            'permissao_usuario_id' => FILTER_VALIDATE_INT
        ]);

        /**
         * @var int $permissao_usuario_id
         */
        extract($get); unset($get);

        try {
            /** @var PermissaoUsuario $permissao_usuario */
            /* @see GetPermissaoUsuarioPorIdCommandHandler */
            $permissao_usuario = $this->command_bus->handle(new GetPermissaoUsuarioPorIdCommand($permissao_usuario_id));

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Editar permissão');
            $this->view->setAtributo('permissao-usuario', $permissao_usuario);

            // Views
            $this->view->addTemplate('permissoes/form_editar_permissao');

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js', false, Configure::get('app', 'versao'));
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
        $post = filter_var_array($request->getParsedBody(), [
            'permissao_usuario_id' => FILTER_VALIDATE_INT,
            'descricao' => FILTER_SANITIZE_STRING
        ]);

        /**
         * @var int $permissao_usuario_id
         * @var string $descricao
         */
        extract($post); unset($post);

        try {
            /** @var PermissaoUsuario $permissao_usuario */
            /* @see EditarPermissaoUsuarioCommandHandler */
            $permissao_usuario = $this->command_bus->handle(new EditarPermissaoUsuarioCommand($permissao_usuario_id, $descricao));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Permissão alterada com sucesso!';
            $msg['permissao_usuario_id'] = $permissao_usuario->getId();
        } catch (UserException $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     */
    public function detalhePermissaoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array($request->getQueryParams(), [
            'permissao_usuario_id' => FILTER_VALIDATE_INT
        ]);

        /**
         * @var int $permissao_usuario_id
         */
        extract($get); unset($get);

        try {
            /** @var PermissaoUsuario $permissao_usuario */
            /* @see GetPermissaoUsuarioPorIdCommandHandler */
            $permissao_usuario = $this->command_bus->handle(new GetPermissaoUsuarioPorIdCommand($permissao_usuario_id));

            if (!$permissao_usuario instanceof PermissaoUsuario) {
                throw new UserException('Permissão de usuário não encontrada.');
            }

            // Atributos
            $this->view->setAtributo('titulo-pagina', "Permissão: {$permissao_usuario->getDescricao()}");
            $this->view->setAtributo('permissao-usuario', $permissao_usuario);

            // Views
            $this->view->addTemplate('permissoes/det_permissao');

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js', false, Configure::get('app', 'versao'));
        } catch (UserException $e) {
            $this->view->addTemplate('commmon/mensagem_usuario');
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
        $post = filter_var_array($request->getParsedBody(), [
            'permissao_usuario_id' => FILTER_VALIDATE_INT
        ]);

        /**
         * @var int $permissao_usuario_id
         */
        extract($post); unset($post);

        try {
            /* @see ExcluirPermissaoUsuarioCommandHandler */
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