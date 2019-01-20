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


use DLX\Contracts\TransacaoInterface;
use DLX\Core\Exceptions\UserException;
use Doctrine\Common\Collections\ArrayCollection;
use League\Tactician\CommandBus;
use PainelDLX\Application\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioHandler;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Presentation\Site\Controllers\SiteController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\VileX;
use Zend\Diactoros\Response\JsonResponse;

class ConfigurarPermissoesController extends SiteController
{
    /**
     * @var TransacaoInterface
     */
    private $transacao;

    /**
     * ConfigurarPermissoesController constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     * @param TransacaoInterface $transacao
     */
    public function __construct(VileX $view, CommandBus $commandBus, TransacaoInterface $transacao)
    {
        parent::__construct($view, $commandBus);

        $this->view->setPaginaMestra('src/Presentation/Site/public/views/painel-dlx-master.phtml');
        $this->view->setViewRoot('src/Presentation/Site/public/views/grupos-usuarios');
        $this->transacao = $transacao;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     */
    public function formConfigurarPermissao(ServerRequestInterface $request): ResponseInterface
    {
        $grupo_usuario_id = filter_var($request->getQueryParams()['grupo_usuario_id'], FILTER_VALIDATE_INT);

        try {
            /**
             * @var GrupoUsuario $grupo_usuario
             * @covers GetGrupoUsuarioPorIdHandler
             */
            $grupo_usuario = $this->command_bus->handle(new GetGrupoUsuarioPorIdCommand($grupo_usuario_id));

            if (!$grupo_usuario instanceof GrupoUsuario) {
                throw new UserException('Grupo de Usuário não identificado.');
            }

            $lista_permissoes = $this->command_bus->handle(new GetListaPermissaoUsuarioCommand(
                ['deletado' => false],
                []
            ));

            // Views
            $this->view->addTemplate('form_configurar_permissoes', [
                'titulo-pagina' => "Gerenciar permissões: {$grupo_usuario->getNome()}",
                'grupo-usuario' => $grupo_usuario,
                'lista-permissoes' => $lista_permissoes
            ]);

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
    public function salvarConfiguracaoPermissao(ServerRequestInterface $request): ResponseInterface
    {
        $post = filter_var_array(
            $request->getParsedBody(),
            [
                'grupo_usuario_id' => FILTER_VALIDATE_INT,
                'permissao_usuario_ids' => [
                    'filter' => FILTER_VALIDATE_INT,
                    'flags' => FILTER_REQUIRE_ARRAY
                ]
            ]
        );

        /**
         * @var int $grupo_usuario_id
         * @var array $permissao_usuario_ids
         */
        extract($post); unset($post);

        try {
            /**
             * @var GrupoUsuario $grupo_usuario
             * @covers GetListaPermissaoUsuarioHandler
             */
            $grupo_usuario = $this->command_bus->handle(new GetGrupoUsuarioPorIdCommand($grupo_usuario_id));

            /** @covers GetListaPermissaoUsuarioHandler */
            $lista_permissoes = new ArrayCollection($this->command_bus->handle(new GetListaPermissaoUsuarioCommand(
                ['permissao_usuario_id' => $permissao_usuario_ids],
                []
            )));

            $this->transacao->begin();
            $this->command_bus->handle(new ConfigurarPermissoesCommand($grupo_usuario, $lista_permissoes));
            $this->transacao->commit();

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Permissões salvas com sucesso!';
        } catch (UserException $e) {
            $this->transacao->rollback();

            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }
}