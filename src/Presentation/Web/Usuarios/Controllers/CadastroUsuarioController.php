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
use Exception;
use League\Tactician\CommandBus;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioInvalidoException;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontradoException;
use PainelDLX\UseCases\GruposUsuarios\GetListaGruposUsuarios\GetListaGruposUsuariosCommand;
use PainelDLX\UseCases\GruposUsuarios\GetListaGruposUsuarios\GetListaGruposUsuariosCommandHandler;
use PainelDLX\UseCases\ListaRegistros\ConverterFiltro2Criteria\ConverterFiltro2CriteriaCommand;
use PainelDLX\UseCases\ListaRegistros\ConverterFiltro2Criteria\ConverterFiltro2CriteriaCommandHandler;
use PainelDLX\UseCases\Usuarios\EditarUsuario\EditarUsuarioCommand;
use PainelDLX\UseCases\Usuarios\EditarUsuario\EditarUsuarioCommandHandler;
use PainelDLX\UseCases\Usuarios\ExcluirUsuario\ExcluirUsuarioCommand;
use PainelDLX\UseCases\Usuarios\ExcluirUsuario\ExcluirUsuarioCommandHandler;
use PainelDLX\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommand;
use PainelDLX\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommandHandler;
use PainelDLX\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommand;
use PainelDLX\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommandHandler;
use PainelDLX\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommand;
use PainelDLX\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommandHandler;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Infrastructure\ORM\Doctrine\Repositories\UsuarioRepository;
use PainelDLX\Presentation\Web\Common\Controllers\PainelDLXController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\TemplateInvalidoException;
use Vilex\VileX;
use Laminas\Diactoros\Response\JsonResponse;

/**
 * Class CadastroUsuarioController
 * @package PainelDLX\Presentation\Web\Controllers
 * @property UsuarioRepository $repository
 */
class CadastroUsuarioController extends PainelDLXController
{
    /**
     * @var GrupoUsuarioRepositoryInterface
     */
    private $grupo_usuario_repository;

    /**
     * CadastroUsuarioController constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     * @param SessionInterface $session
     * @param GrupoUsuarioRepositoryInterface $grupo_usuario_repository
     * @throws TemplateInvalidoException
     */
    public function __construct(
        VileX $view,
        CommandBus $commandBus,
        SessionInterface $session,
        GrupoUsuarioRepositoryInterface $grupo_usuario_repository
    ) {
        parent::__construct($view, $commandBus, $session);
        $this->grupo_usuario_repository = $grupo_usuario_repository;
    }

    /**
     * Listar os usuários existentes no banco de dados
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function listaUsuarios(ServerRequestInterface $request): ResponseInterface
    {
        $get = $request->getQueryParams();

        try {
            /** @var array $criteria */
            /* @see ConverterFiltro2CriteriaCommandHandler */
            $criteria = $this->command_bus->handle(new ConverterFiltro2CriteriaCommand($get['campos'], $get['busca']));

            /* @see GetListaUsuariosCommandHandler */
            $lista_usuarios = $this->command_bus->handle(new GetListaUsuariosCommand($criteria, [], $get['qtde'], $get['offset']));

            $this->view->setAtributo('titulo-pagina', 'Usuários');

            // Lista
            $this->view->addTemplate('usuarios/lista_usuarios', [
                'lista-usuarios' => $lista_usuarios,
                'filtro' => $get
            ]);

            // Paginação
            $this->view->addTemplate('common/paginacao', [
                'pagina-atual' => $get['pg'],
                'qtde-registros-pagina' => $get['qtde'],
                'qtde-registros-lista' => count($lista_usuarios)
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
     * @return ResponseInterface
     * @throws Exception
     */
    public function formNovoUsuario(): ResponseInterface
    {
        try {
            /** @var array $lista_grupos */
            /* @see GetListaGruposUsuariosCommandHandler */
            $lista_grupos = $this->command_bus->handle(new GetListaGruposUsuariosCommand());

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Adicionar novo usuário');

            // Views
            $this->view->addTemplate('usuarios/form_novo_usuario', [
                'lista-grupos' => $lista_grupos
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
     * Cadastrar um novo usuário.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function cadastrarNovoUsuario(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var string $nome
         * @var string $email
         * @var string $senha
         * @var string $senha_confirm,
         * @var array $grupos
         */
        extract($request->getParsedBody());

        try {
            $grupos = $this->grupo_usuario_repository->getListaGruposByIds(...$grupos);

            /** @var Usuario $usuario */
            /* @see NovoUsuarioCommandHandler */
            $usuario = $this->command_bus->handle(new NovoUsuarioCommand(
                $nome,
                $email,
                $senha,
                $senha_confirm,
                $grupos
            ));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Usuário cadastrado com sucesso!';
            $msg['usuario_id'] = $usuario->getId();
        } catch (Exception $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    /**
     * Mostrar formulário para alterar informações do usuário.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function formAlterarUsuario(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array($request->getQueryParams(), ['usuario_id' => FILTER_VALIDATE_INT]);

        try {
            /** @var Usuario|null $usuario */
            /* @see GetUsuarioPeloIdCommandHandler */
            $usuario = $this->command_bus->handle(new GetUsuarioPeloIdCommand($get['usuario_id']));

            /** @var GrupoUsuario|null $lista_grupos */
            /* @see GetListaGruposUsuariosCommandHandler */
            $lista_grupos = $this->command_bus->handle(new GetListaGruposUsuariosCommand());

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Atualizar informações do usuário');

            // views
            $this->view->addTemplate('usuarios/form_alterar_usuario', [
                'usuario' => $usuario,
                'lista-grupos' => $lista_grupos
            ]);

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js', false, VERSAO_PAINEL_DLX);
        } catch (UsuarioNaoEncontradoException | UserException $e) {
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
     * Atualizar as informações de um usuário existente.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function atualizarUsuarioExistente(ServerRequestInterface $request): ResponseInterface
    {
        $post = filter_var_array($request->getParsedBody(), [
            'usuario_id' => FILTER_VALIDATE_INT,
            'nome' => FILTER_SANITIZE_STRING,
            'email' => FILTER_VALIDATE_EMAIL,
            'grupos' => ['filter' => FILTER_VALIDATE_INT, 'flags' => FILTER_REQUIRE_ARRAY]
        ]);

        /**
         * @var int $usuario_id
         * @var string $nome
         * @var string $email
         * @var array $grupos
         */
        extract($post); unset($post);

        try {
            /** @var Usuario $usuario */
            /* @see GetUsuarioPeloIdCommandHandler */
            $usuario = $this->command_bus->handle(new GetUsuarioPeloIdCommand($usuario_id));

            /* @see EditarUsuarioCommandHandler */
            $this->command_bus->handle(new EditarUsuarioCommand(
                $usuario,
                $nome,
                $email,
                $grupos
            ));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Usuário atualizado com sucesso!';
            $msg['usuario_id'] = $usuario->getId();
        } catch (UsuarioNaoEncontradoException | UsuarioInvalidoException | Exception $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function excluirUsuario(ServerRequestInterface $request): ResponseInterface
    {
        $post = filter_var_array($request->getParsedBody(), ['usuario_id' => FILTER_VALIDATE_INT]);

        try {
            /** @var Usuario|null $usuario */
            /* @see GetUsuarioPeloIdCommandHandler */
            $usuario = $this->command_bus->handle(new GetUsuarioPeloIdCommand($post['usuario_id']));

            /* @see ExcluirUsuarioCommandHandler */
            $this->command_bus->handle(new ExcluirUsuarioCommand($usuario));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Usuário excluído com sucesso!';
        } catch (UsuarioNaoEncontradoException $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws TemplateInvalidoException
     * @throws PaginaMestraInvalidaException
     */
    public function detalheUsuario(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array($request->getQueryParams(), ['usuario_id' => FILTER_VALIDATE_INT]);

        try {
            /** @var Usuario|null $usuario */
            /* @see GetUsuarioPeloIdCommandHandler */
            $usuario = $this->command_bus->handle(new GetUsuarioPeloIdCommand($get['usuario_id']));

            // Atributos
            $this->view->setAtributo('titulo-pagina', $usuario->getNome());

            // views
            $this->view->addTemplate('usuarios/det_usuario', [
                'usuario' => $usuario,
                'is-usuario-logado' => false
            ]);
        } catch (UsuarioNaoEncontradoException | UserException $e) {
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