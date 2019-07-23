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


use DLX\Contracts\TransactionInterface;
use DLX\Core\Configure;
use DLX\Core\Exceptions\UserException;
use Doctrine\Common\Collections\ArrayCollection;
use League\Tactician\CommandBus;
use PainelDLX\Domain\GruposUsuarios\Exceptions\GrupoUsuarioNaoEncontradoException;
use PainelDLX\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesCommand;
use PainelDLX\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommand;
use PainelDLX\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommandHandler;
use PainelDLX\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesCommandHandler;
use PainelDLX\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioCommand;
use PainelDLX\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioCommandHandler;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Presentation\Site\Common\Controllers\PainelDLXController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Vilex\VileX;
use Zend\Diactoros\Response\JsonResponse;

class ConfigurarPermissoesController extends PainelDLXController
{
    /**
     * @var TransactionInterface
     */
    private $transaction;

    /**
     * ConfigurarPermissoesController constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     * @param TransactionInterface $transacao
     * @param SessionInterface $session
     * @throws ViewNaoEncontradaException
     */
    public function __construct(
        VileX $view,
        CommandBus $commandBus,
        SessionInterface $session,
        TransactionInterface $transacao
    ) {
        parent::__construct($view, $commandBus, $session);
        $this->transaction = $transacao;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     */
    public function formConfigurarPermissao(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array($request->getQueryParams(), [
            'grupo_usuario_id' => FILTER_VALIDATE_INT
        ]);

        try {
            /** @var GrupoUsuario $grupo_usuario */
            /* @see GetGrupoUsuarioPorIdCommandHandler */
            $grupo_usuario = $this->command_bus->handle(new GetGrupoUsuarioPorIdCommand($get['grupo_usuario_id']));

            if (!$grupo_usuario instanceof GrupoUsuario) {
                throw new UserException('Grupo de Usuário não identificado.');
            }

            /* @see GetListaPermissaoUsuarioCommandHandler */
            $lista_permissoes = $this->command_bus->handle(new GetListaPermissaoUsuarioCommand(
                ['deletado' => false],
                []
            ));

            // Views
            $this->view->addTemplate('grupos-usuarios/form_configurar_permissoes');

            // Parâmetros
            $this->view->setAtributo('titulo-pagina', "Gerenciar permissões: {$grupo_usuario->getNome()}");
            $this->view->setAtributo('grupo-usuario', $grupo_usuario);
            $this->view->setAtributo('lista-permissoes', $lista_permissoes);

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js', false, Configure::get('app', 'versao'));
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
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function salvarConfiguracaoPermissao(ServerRequestInterface $request): ResponseInterface
    {
        $post = filter_var_array($request->getParsedBody(), [
            'grupo_usuario_id' => FILTER_VALIDATE_INT,
            'permissao_usuario_ids' => [
                'filter' => FILTER_VALIDATE_INT,
                'flags' => FILTER_REQUIRE_ARRAY
            ]
        ]);

        /**
         * @var int $grupo_usuario_id
         * @var array $permissao_usuario_ids
         */
        extract($post); unset($post);

        try {
            /** @var GrupoUsuario $grupo_usuario */
            /* @see GetListaPermissaoUsuarioCommandHandler */
            $grupo_usuario = $this->command_bus->handle(new GetGrupoUsuarioPorIdCommand($grupo_usuario_id));

            /* @see GetListaPermissaoUsuarioCommandHandler */
            $lista_permissoes = new ArrayCollection($this->command_bus->handle(new GetListaPermissaoUsuarioCommand(
                ['id' => $permissao_usuario_ids]
            )));

            $this->transaction->transactional(function () use ($grupo_usuario, $lista_permissoes) {
                /* @see ConfigurarPermissoesCommandHandler */
                $this->command_bus->handle(new ConfigurarPermissoesCommand($grupo_usuario, $lista_permissoes));
            });

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Permissões salvas com sucesso!';
        } catch (GrupoUsuarioNaoEncontradoException | UserException $e) {
            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }
}