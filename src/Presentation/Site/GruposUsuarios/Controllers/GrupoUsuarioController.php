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

namespace PainelDLX\Presentation\Site\GruposUsuarios\Controllers;


use DLX\Core\Configure;
use DLX\Core\Exceptions\UserException;
use Exception;
use PainelDLX\Domain\GruposUsuarios\Exceptions\GrupoUsuarioNaoEncontradoException;
use PainelDLX\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioCommand;
use PainelDLX\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioCommandHandler;
use PainelDLX\UseCases\GruposUsuarios\ExcluirGrupoUsuario\ExcluirGrupoUsuarioCommand;
use PainelDLX\UseCases\GruposUsuarios\ExcluirGrupoUsuario\ExcluirGrupoUsuarioCommandHandler;
use PainelDLX\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommand;
use PainelDLX\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommandHandler;
use PainelDLX\UseCases\GruposUsuarios\GetListaGruposUsuarios\GetListaGruposUsuariosCommandHandler;
use PainelDLX\UseCases\GruposUsuarios\GetListaGruposUsuarios\GetListaGruposUsuariosCommand;
use PainelDLX\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioCommand;
use PainelDLX\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioCommandHandler;
use PainelDLX\UseCases\ListaRegistros\ConverterFiltro2Criteria\ConverterFiltro2CriteriaCommand;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Infrastructure\ORM\Doctrine\Repositories\GrupoUsuarioRepository;
use PainelDLX\Presentation\Site\Common\Controllers\PainelDLXController;
use PainelDLX\UseCases\ListaRegistros\ConverterFiltro2Criteria\ConverterFiltro2CriteriaCommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class GrupoUsuarioController
 * @package PainelDLX\Presentation\Site\Controllers
 * @property GrupoUsuarioRepository $repository
 */
class GrupoUsuarioController extends PainelDLXController
{
    /**
     * Mostrar a lista com os usuários
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PaginaMestraNaoEncontradaException
     * @throws Exception
     */
    public function listaGruposUsuarios(ServerRequestInterface $request): ResponseInterface
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

            /** @var array $lista_grupos_usuarios */
            /* @see GetListaGruposUsuariosCommandHandler */
            $lista_grupos_usuarios = $this->command_bus->handle(new GetListaGruposUsuariosCommand($criteria, [], $get['qtde'], $get['offset']));

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Grupos de Usuários');
            $this->view->setAtributo('lista_grupos_usuarios', $lista_grupos_usuarios);
            $this->view->setAtributo('filtro', $get);

            // Paginação
            $this->view->setAtributo('pagina-atual', $get['pg']);
            $this->view->setAtributo('qtde-registros-pagina', $get['qtde']);
            $this->view->setAtributo('qtde-registros-lista', count($lista_grupos_usuarios));

            // Views
            $this->view->addTemplate('grupos-usuarios/lista_grupos_usuarios');
            $this->view->addTemplate('common/paginacao');
        } catch (UserException $e) {
            $this->view->addTemplate('common/mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'texto' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }

    /**
     * @return ResponseInterface
     * @throws Exception
     */
    public function formNovoGrupoUsuario(): ResponseInterface
    {
        try {
            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Adicionar novo grupo de usuário');

            // Views
            $this->view->addTemplate('grupos-usuarios/form_novo_grupo_usuario');

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js', false, VERSAO_PAINEL_DLX);
        } catch (UserException $e) {
            $this->view->addTemplate('commmon/mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'texto' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }

    /**
     * Cadastrar um novo grupo de usuário.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function cadastrarNovoGrupoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var string $nome
         */
        extract($request->getParsedBody());

        try {
            /** @var GrupoUsuario $grupo_usuario */
            /* @see NovoGrupoUsuarioCommandHandler */
            $grupo_usuario = $this->command_bus->handle(new NovoGrupoUsuarioCommand($nome));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Grupo de usuário cadastrado com sucesso!';
            $msg['grupo_usuario_id'] = $grupo_usuario->getId();
        } catch (Exception $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    /**
     * Mostrar o formulário para alterar as informações de um grupo de usuário
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PaginaMestraNaoEncontradaException
     * @throws Exception
     */
    public function formAlterarGrupoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var int $grupo_usuario_id
         */
        extract($request->getQueryParams());

        try {
            /** @var GrupoUsuario $grupo_usuario */
            /* @see GetGrupoUsuarioPorIdCommandHandler */
            $grupo_usuario = $this->command_bus->handle(new GetGrupoUsuarioPorIdCommand($grupo_usuario_id));

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Atualizar informações do grupo de usuário');
            $this->view->setAtributo('grupo_usuario', $grupo_usuario);

            // Views
            $this->view->addTemplate('grupos-usuarios/form_alterar_grupo_usuario');

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js', false, VERSAO_PAINEL_DLX);
        } catch (UserException $e) {
            $this->view->addTemplate('common/mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'texto' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }

    /**
     * Alterar as informações de um grupo de usuário
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function atualizarGrupoUsuarioExistente(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var int $grupo_usuario_id
         * @var string $nome
         */
        extract($request->getParsedBody());

        try {
            /** @var GrupoUsuario $grupo_usuario_atualizado */
            /* @see EditarGrupoUsuarioCommandHandler */
            $grupo_usuario_atualizado = $this->command_bus->handle(new EditarGrupoUsuarioCommand($grupo_usuario_id, $nome));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Grupo de usuário atualizado com sucesso!';
            $msg['grupo_usuario_id'] = $grupo_usuario_atualizado->getId();
        } catch (Exception $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    /**
     * Excluir um determinado grupo de usuário.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function excluirGrupoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var int $grupo_usuario_id
         */
        extract($request->getParsedBody());

        try {
            /** @var GrupoUsuario $grupo_usuario */
            /* @see GetGrupoUsuarioPorIdCommandHandler */
            $grupo_usuario = $this->command_bus->handle(new GetGrupoUsuarioPorIdCommand($grupo_usuario_id));

            /* @see ExcluirGrupoUsuarioCommandHandler */
            $this->command_bus->handle(new ExcluirGrupoUsuarioCommand($grupo_usuario));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Grupo de usuário excluído com sucesso!';
        } catch (GrupoUsuarioNaoEncontradoException | Exception $e) {
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
    public function detalheGrupoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var int $grupo_usuario_id
         */
        extract($request->getQueryParams());

        try {
            /** @var GrupoUsuario $usuario */
            /* @see GetGrupoUsuarioPorIdCommandHandler */
            $grupo_usuario = $this->command_bus->handle(new GetGrupoUsuarioPorIdCommand($grupo_usuario_id));

            // Atributos
            $this->view->setAtributo('titulo-pagina', "Grupo de Usuário: {$grupo_usuario->getNome()}");
            $this->view->setAtributo('grupo-usuario', $grupo_usuario);

            // views
            $this->view->addTemplate('grupos-usuarios/det_grupo_usuario');
        } catch (UserException $e) {
            $this->view->addTemplate('mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'texto' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }
}