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

namespace PainelDLX\Presentation\Site\Emails\Controllers;


use DLX\Core\Configure;
use DLX\Core\Exceptions\UserException;
use League\Tactician\CommandBus;
use mysql_xdevapi\Session;
use PainelDLX\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpCommand;
use PainelDLX\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpHandler;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Presentation\Site\Common\Controllers\PainelDLXController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Vilex\VileX;
use Zend\Diactoros\Response\JsonResponse;

class NovaConfigSmtpController extends PainelDLXController
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * NovaConfigSmtpController constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     * @param SessionInterface $session
     */
    public function __construct(VileX $view, CommandBus $commandBus, SessionInterface $session)
    {
        parent::__construct($view, $commandBus);

        $this->view->setPaginaMestra("public/views/paginas-mestras/{$session->get('vilex:pagina-mestra')}.phtml");
        $this->view->setViewRoot('public/views/');
        $this->session = $session;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     * @throws ContextoInvalidoException
     */
    public function formNovaConfigSmtp(ServerRequestInterface $request): ResponseInterface
    {
        try {
            // View
            $this->view->addTemplate('emails/form_config_smtp');

            // Parâmetros
            $this->view->setAtributo('titulo-pagina', 'Nova configuração SMTP');

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
    public function salvarNovaConfigSmtp(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var string $nome
         * @var string $servidor
         * @var int $porta
         * @var string|null $cripto
         * @var bool $requer_autent
         * @var string|null $conta
         * @var string|null $senha
         * @var string|null $de_nome
         * @var string|null $responder_para
         * @var bool $corpo_html
         */
        extract($request->getParsedBody());

        try {
            $config_smtp = new ConfigSmtp($servidor, $porta);
            $config_smtp
                ->setNome($nome)
                ->setCripto($cripto)
                ->setRequerAutent($requer_autent)
                ->setConta($conta)
                ->setSenha($senha)
                ->setDeNome($de_nome)
                ->setResponderPara($responder_para)
                ->setCorpoHtml($corpo_html);

            /* @see NovaConfigSmtpHandler */
            $this->command_bus->handle(new NovaConfigSmtpCommand($config_smtp));

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Configuração SMTP salva com sucesso!';
            $json['config_smtp_id'] = $config_smtp->getId();
        } catch (UserException $e) {
            $json['erro'] = 'sucesso';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }
}