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

namespace PainelDLX\Presentation\Site\Controllers;

use DLX\Core\Exceptions\UserException;
use DLX\Infra\EntityManagerX;
use Grpc\Server;
use PainelDLX\Application\CadastroUsuarios\Commands\CadastrarNovoUsuarioCommand;
use PainelDLX\Application\CadastroUsuarios\Commands\ExcluirUsuarioCommand;
use PainelDLX\Application\CadastroUsuarios\Commands\SalvarUsuarioExistenteCommand;
use PainelDLX\Application\CadastroUsuarios\Handlers\CadastrarNovoUsuarioHandler;
use PainelDLX\Application\CadastroUsuarios\Handlers\ExcluirUsuarioHandler;
use PainelDLX\Application\CadastroUsuarios\Handlers\SalvarUsuarioExistenteHandler;
use PainelDLX\Domain\CadastroUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\CadastroUsuarios\Entities\Usuario;
use PainelDLX\Infra\ORM\Doctrine\Repositories\GrupoUsuarioRepository;
use PainelDLX\Infra\ORM\Doctrine\Repositories\UsuarioRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class CadastroUsuarioController
 * @package PainelDLX\Presentation\Site\Controllers
 * @property UsuarioRepository $repository
 */
class CadastroUsuarioController extends SiteController
{
    /**
     * CadastroUsuarioController constructor.
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct()
    {
        parent::__construct();

        $this->view->setPaginaMestra('src/PainelDLX/Presentation/Site/Views/painel-dlx-master.phtml');
        $this->view->setViewRoot('src/PainelDLX/Presentation/Site/Views');

        $this->repository = EntityManagerX::getRepository(Usuario::class);
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
            $this->view->setAtributo('titulo-pagina', 'Adicionar novo usuário');
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
            /** @var GrupoUsuarioRepository $grupo_usuario_repository */
            $grupo_usuario_repository = EntityManagerX::getRepository(GrupoUsuario::class);
            $lista_grupos = $grupo_usuario_repository->findAtivos();

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Adicionar novo usuário');
            $this->view->setAtributo('lista_grupos', $lista_grupos);

            // Views
            $this->view->addTemplate('form_novo_usuario');
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
        $dados_usuario = $request->getParsedBody();

        try {
            $command = (new CadastrarNovoUsuarioCommand())
                ->setNome($dados_usuario['nome'])
                ->setEmail($dados_usuario['email'])
                ->setSenha($dados_usuario['senha'])
                ->setSenhaConfirm($dados_usuario['senha_confirm'])
                ->setGrupos(...$dados_usuario['grupos']);

            /** @var GrupoUsuarioRepository $grupo_usuario_repository */
            $grupo_usuario_repository = EntityManagerX::getRepository(GrupoUsuario::class);

            /** @var Usuario $usuario */
            $usuario = (new CadastrarNovoUsuarioHandler($this->repository, $grupo_usuario_repository))
                ->handle($command);

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Usuário cadastrado com sucesso!';
            $msg['usuario'] = $usuario;
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

            /** @var GrupoUsuarioRepository $grupo_usuario_repository */
            $grupo_usuario_repository = EntityManagerX::getRepository(GrupoUsuario::class);
            $lista_grupos = $grupo_usuario_repository->findAtivos();

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Atualizar informações do usuário');
            $this->view->setAtributo('usuario', $usuario);
            $this->view->setAtributo('lista_grupos', $lista_grupos);

            // Views
            $this->view->addTemplate('form_alterar_usuario');
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
        $dados_usuario = $request->getParsedBody();

        try {
            $command = (new SalvarUsuarioExistenteCommand())
                ->setUsuarioId($dados_usuario['usuario_id'])
                ->setNome($dados_usuario['nome'])
                ->setEmail($dados_usuario['email'])
                ->setGrupos(...$dados_usuario['grupos']);

            /** @var GrupoUsuarioRepository $grupo_usuario_repository */
            $grupo_usuario_repository = EntityManagerX::getRepository(GrupoUsuario::class);

            $usuario_atualizado = (new SalvarUsuarioExistenteHandler($this->repository, $grupo_usuario_repository))
                ->handle($command);

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Usuário atualizado com sucesso!';
            $msg['usuario'] = $usuario_atualizado;
        } catch (\Exception $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }

    public function excluirUsuario(ServerRequestInterface $request): ResponseInterface
    {
        $usuario_id = $request->getParsedBody()['usuario_id'];

        try {
            /** @var Usuario $usuario */
            $usuario = $this->repository->find($usuario_id);
            $command = (new ExcluirUsuarioCommand($usuario));

            (new ExcluirUsuarioHandler($this->repository))->handle($command);

            $msg['retorno'] = 'sucesso';
            $msg['mensagem'] = 'Usuário excluído com sucesso!';
        } catch (\Exception $e) {
            $msg['retorno'] = 'erro';
            $msg['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($msg);
    }
}