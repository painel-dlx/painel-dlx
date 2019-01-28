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
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontrado;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\ValueObjects\SenhaUsuario;
use PainelDLX\Presentation\Site\Controllers\SiteController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\VileX;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class AlterarSenhaUsuarioController
 * @package PainelDLX\Presentation\Site\Controllers
 * @property UsuarioRepositoryInterface repository
 */
class AlterarSenhaUsuarioController extends SiteController
{
    /**
     * AlterarSenhaUsuarioController constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     * @param UsuarioRepositoryInterface $usuario_repository
     */
    public function __construct(
        VileX $view,
        CommandBus $commandBus,
        UsuarioRepositoryInterface $usuario_repository
    ) {
        parent::__construct($view, $commandBus);

        $this->view->setPaginaMestra('src/Presentation/Site/public/views/paginas-mestras/painel-dlx-master.phtml');
        $this->view->setViewRoot('src/Presentation/Site/public/views');

        $this->repository = $usuario_repository;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     */
    public function formAlterarSenha(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var($request->getQueryParams(), [
            'usuario_id' => FILTER_VALIDATE_INT
        ]);

        try {
            /** @var Usuario $usuario */
            $usuario = $this->repository->find($get['usuario_id']);

            // Atributos
            $this->view->setAtributo('titulo-pagina', 'Alterar senha');
            $this->view->setAtributo('usuario', $usuario);

            // Views
            $this->view->addTemplate('form_alterar_senha');

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
    public function alterarSenhaUsuario(ServerRequestInterface $request): ResponseInterface
    {
        $post = filter_var_array($request->getParsedBody(), [
            'usuario_id' => FILTER_VALIDATE_INT,
            'senha_atual' => FILTER_DEFAULT,
            'senha_nova' => FILTER_DEFAULT,
            'senha_confirm' => FILTER_DEFAULT
        ]);

        /**
         * @var int $usuario_id
         * @var string $senha_atual
         * @var string $senha_nova
         * @var string $senha_confirm
         */
        extract($post); unset($post);

        try {
            /** @var Usuario $usuario */
            $usuario = $this->repository->find($usuario_id);

            if (!$usuario instanceof Usuario) {
                throw new UsuarioNaoEncontrado();
            }

            $senha_usuario = new SenhaUsuario($senha_nova, $senha_confirm, $senha_atual);
            $this->command_bus->handle(new AlterarSenhaUsuarioCommand($usuario, $senha_usuario));

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Senha alterada com sucesso!';
        } catch (UserException $e) {
            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }
}