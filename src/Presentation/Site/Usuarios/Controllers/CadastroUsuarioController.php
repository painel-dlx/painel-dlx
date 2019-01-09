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
use PainelDLX\Application\UseCases\Usuarios\EditarUsuario\EditarUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\EditarUsuario\EditarUsuarioHandler;
use PainelDLX\Application\UseCases\Usuarios\ExcluirUsuario\ExcluirUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\ExcluirUsuario\ExcluirUsuarioHandler;
use PainelDLX\Application\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\NovoUsuario\NovoUsuarioHandler;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Infra\ORM\Doctrine\Repositories\UsuarioRepository;
use PainelDLX\Presentation\Site\Controllers\SiteController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\VileX;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class CadastroUsuarioController
 * @package PainelDLX\Presentation\Site\Controllers
 * @property UsuarioRepository $repository
 */
class CadastroUsuarioController extends SiteController
{
    /** @var GrupoUsuarioRepositoryInterface */
    private $grupo_usuario_repository;

    /**
     * CadastroUsuarioController constructor.
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct(
        VileX $view,
        CommandBus $commandBus,
        UsuarioRepositoryInterface $usuario_repository,
        GrupoUsuarioRepositoryInterface $grupo_usuario_repository
    ) {
        parent::__construct($view, $commandBus);

        $this->view->setPaginaMestra('src/Presentation/Site/public/views/painel-dlx-master.phtml');
        $this->view->setViewRoot('src/Presentation/Site/public/views/usuarios');

        $this->repository = $usuario_repository;
        $this->grupo_usuario_repository = $grupo_usuario_repository;
    }

    /**
     * Listar os usuários existentes no banco de dados
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Exception
     */
    public function listaUsuarios(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $lista_usuarios = $this->repository->findBy($request->getParsedBody());

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Usuários');
            $this->view->setAtributo('lista_usuarios', $lista_usuarios);

            // Views
            $this->view->addTemplate('lista_usuarios');
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
     * @return ResponseInterface
     * @throws \Exception
     */
    public function formNovoUsuario(): ResponseInterface
    {
        try {
            $lista_grupos = $this->grupo_usuario_repository->findAtivos();

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Adicionar novo usuário');
            $this->view->setAtributo('lista_grupos', $lista_grupos);

            // Views
            $this->view->addTemplate('form_novo_usuario');

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
     * Cadastrar um novo usuário.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
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
            $usuario = Usuario::create($nome, $email, ...$grupos)
                ->setSenha($senha);

            /** @covers NovoUsuarioHandler */
            $this->commandBus->handle(new NovoUsuarioCommand($usuario, $senha_confirm));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Usuário cadastrado com sucesso!';
            $msg['usuario'] = $usuario->getUsuarioId();
        } catch (\Exception $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    /**
     * Mostrar formulário para alterar informações do usuário.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Exception
     */
    public function formAlterarUsuario(ServerRequestInterface $request): ResponseInterface
    {
        $usuario_id = $request->getQueryParams()['usuario_id'];

        try {
            /** @var Usuario $usuario */
            $usuario = $this->repository->find($usuario_id);
            $lista_grupos = $this->grupo_usuario_repository->findAtivos();

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Atualizar informações do usuário');
            $this->view->setAtributo('usuario', $usuario);
            $this->view->setAtributo('lista_grupos', $lista_grupos);

            // views
            $this->view->addTemplate('form_alterar_usuario');

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
     * Atualizar as informações de um usuário existente.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function atualizarUsuarioExistente(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var int $usuario_id
         * @var string $nome
         * @var string $email
         * @var array $grupos
         */
        extract($request->getParsedBody());

        try {
            /**
             * @var Usuario $usuario_atualizado
             * @covers EditarUsuarioHandler
             */
            $usuario_atualizado = $this->commandBus->handle(new EditarUsuarioCommand($usuario_id, $nome, $email, $grupos));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Usuário atualizado com sucesso!';
            $msg['usuario'] = $usuario_atualizado->getUsuarioId();
        } catch (\Exception $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    public function excluirUsuario(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var int $usuario_id
         */
        extract($request->getParsedBody());

        try {
            /** @var Usuario $usuario */
            $usuario = $this->repository->find($usuario_id);

            /** @covers ExcluirUsuarioHandler */
            $this->commandBus->handle(new ExcluirUsuarioCommand($usuario));

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Usuário excluído com sucesso!';
        } catch (\Exception $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     */
    public function detalheUsuario(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var int $usuario_id
         */
        extract($request->getQueryParams());

        try {
            /** @var Usuario $usuario */
            $usuario = $this->repository->find($usuario_id);

            // Atributos
            $this->view->setAtributo('titulo-pagina', $usuario->getNome());
            $this->view->setAtributo('usuario', $usuario);
            $this->view->setAtributo('is-usuario-logado', false);

            // views
            $this->view->addTemplate('det_usuario');
        } catch (UserException $e) {
            $this->view->addTemplate('mensagem_usuario');
            $this->view->setAtributo('mensagem', [
                'tipo' => 'erro',
                'mensagem' => $e->getMessage()
            ]);
        }

        return $this->view->render();
    }
}