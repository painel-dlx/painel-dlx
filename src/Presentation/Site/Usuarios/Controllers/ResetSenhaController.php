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


use DLX\Contracts\TransactionInterface;
use DLX\Core\Configure;
use DLX\Core\Exceptions\UserException;
use DLX\Infrastructure\EntityManagerX;
use Doctrine\ORM\ORMException;
use League\Tactician\CommandBus;
use PainelDLX\Application\Services\Exceptions\ErroAoEnviarEmailException;
use PainelDLX\Domain\Usuarios\Exceptions\ResetSenhaNaoEncontradoException;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontradoException;
use PainelDLX\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommand;
use PainelDLX\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommandHandler;
use PainelDLX\UseCases\Usuarios\EnviarEmailResetSenha\EnviarEmailResetSenhaCommand;
use PainelDLX\UseCases\Usuarios\EnviarEmailResetSenha\EnviarEmailResetSenhaCommandHandler;
use PainelDLX\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashCommand;
use PainelDLX\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashCommandHandler;
use PainelDLX\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommand;
use PainelDLX\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommandHandler;
use PainelDLX\UseCases\Usuarios\UtilizarResetSenha\UtilizarResetSenhaCommand;
use PainelDLX\UseCases\Usuarios\UtilizarResetSenha\UtilizarResetSenhaCommandHandler;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\ValueObjects\SenhaUsuario;
use PainelDLX\Presentation\Site\Common\Controllers\PainelDLXController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Vilex\VileX;
use Zend\Diactoros\Response\JsonResponse;

class ResetSenhaController extends PainelDLXController
{
    /**
     * @var TransactionInterface
     */
    private $transaction;

    /**
     * ResetSenhaController constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     * @param SessionInterface $session
     * @param TransactionInterface $transacao
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
    public function formSolicitarResetSenha(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // Views
            $this->view->addTemplate('login/form_reset_senha', [
                'titulo-pagina' => 'Recuperação de senha'
            ]);

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
    public function solicitarResetSenha(ServerRequestInterface $request): ResponseInterface
    {
        $email = filter_var($request->getParsedBody()['email'], FILTER_VALIDATE_EMAIL);

        try {
            $json = [];

            // @todo: melhorar o retorno na variável $json
            $this->transaction->transactional(function () use ($email, &$json) {
                /** @var ResetSenha $reset_senha */
                /* @see SolicitarResetSenhaCommandHandler */
                $reset_senha = $this->command_bus->handle(new SolicitarResetSenhaCommand($email));

                /* @see EnviarEmailResetSenhaCommandHandler */
                $this->command_bus->handle(new EnviarEmailResetSenhaCommand($reset_senha));

                $json['reset_senha_id'] = $reset_senha->getId();
            });

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Foi enviado um email com instruções para recuperar sua senha.';
        } catch (UsuarioNaoEncontradoException | ErroAoEnviarEmailException | UserException $e) {
            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     */
    public function formResetSenha(ServerRequestInterface $request): ResponseInterface
    {
        $hash = filter_var($request->getQueryParams()['hash']);

        try {
            /* @see GetResetSenhaPorHashCommandHandler */
            $reset_senha = $this->command_bus->handle(new GetResetSenhaPorHashCommand($hash));

            $this->session->set('hash', $hash);

            // Views
            $this->view->addTemplate('login/form_resetar_senha');

            // Parâmetros
            $this->view->setAtributo('titulo-pagina', 'Recuperação de senha');
            $this->view->setAtributo('reset-senha', $reset_senha);

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js', false, Configure::get('app', 'versao'));
        } catch (ResetSenhaNaoEncontradoException | UserException $e) {
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
    public function resetarSenha(ServerRequestInterface $request): ResponseInterface
    {
        $hash = $this->session->get('hash');

        $post = filter_var_array($request->getParsedBody(), [
            'senha_nova' => FILTER_DEFAULT,
            'senha_confirm' => FILTER_DEFAULT
        ]);

        /**
         * @var string $senha_nova
         * @var string $senha_confirm
         */
        extract($post); unset($post);

        try {
            $senha_usuario = new SenhaUsuario($senha_nova, $senha_confirm, null, true);

            /* @see GetResetSenhaPorHashCommandHandler */
            $reset_senha = $this->command_bus->handle(new GetResetSenhaPorHashCommand($hash));

            $this->transaction->transactional(function () use ($reset_senha, $senha_usuario) {
                /* @see AlterarSenhaUsuarioCommandHandler */
                $this->command_bus->handle(new AlterarSenhaUsuarioCommand($reset_senha->getUsuario(), $senha_usuario));

                /* @see UtilizarResetSenhaCommandHandler */
                $this->command_bus->handle(new UtilizarResetSenhaCommand($reset_senha));
            });

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Senha alterada com sucesso! Faça login no sistema com sua nova senha.';
        } catch (ResetSenhaNaoEncontradoException | ErroAoEnviarEmailException | UserException $e) {
            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }
}