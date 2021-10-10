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

namespace PainelDLX\Presentation\Web\Emails\Controllers;


use DLX\Core\Exceptions\UserException;
use PainelDLX\Domain\Emails\Exceptions\ConfigSmtpInvalidoException;
use PainelDLX\Domain\Emails\Exceptions\ConfigSmtpNaoEncontradaException;
use PainelDLX\UseCases\Emails\EditarConfigSmtp\EditarConfigSmtpCommand;
use PainelDLX\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommand;
use PainelDLX\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommandHandler;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Presentation\Web\Common\Controllers\PainelDLXController;
use PainelDLX\UseCases\Emails\EditarConfigSmtp\EditarConfigSmtpCommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\TemplateInvalidoException;
use Laminas\Diactoros\Response\JsonResponse;

/**
 * Class EditarConfigSmtpController
 * @package PainelDLX\Presentation\Web\Emails\Controllers
 * @covers EditarConfigSmtpControllerTest
 */
class EditarConfigSmtpController extends PainelDLXController
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     */
    public function formEditarConfigSmtp(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array($request->getQueryParams(), [
            'config_smtp_id' => FILTER_VALIDATE_INT
        ]);

        try {
            /** @var ConfigSmtp $config_smtp */
            /* @see GetConfigSmtpPorIdCommandHandler */
            $config_smtp = $this->command_bus->handle(new GetConfigSmtpPorIdCommand($get['config_smtp_id']));

            // View
            $this->view->addTemplate('emails/form_config_smtp', [
                'config-smtp' => $config_smtp
            ]);

            // Parâmetros
            $this->view->setAtributo('titulo-pagina', "Editar configuração SMTP: {$config_smtp->getNome()}");

            // JS
            $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js', false, VERSAO_PAINEL_DLX);
        } catch (ConfigSmtpNaoEncontradaException | UserException $e) {
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
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function editarConfigSmtp(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var int $config_smtp_id
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
            /** @var ConfigSmtp $config_smtp */
            /* @see GetConfigSmtpPorIdCommandHandler */
            $config_smtp = $this->command_bus->handle(new GetConfigSmtpPorIdCommand($config_smtp_id));

            /* @see EditarConfigSmtpCommandHandler */
            $this->command_bus->handle(new EditarConfigSmtpCommand(
                $config_smtp,
                $nome,
                $servidor,
                $porta,
                $cripto,
                $requer_autent,
                $conta,
                $senha,
                $de_nome,
                $responder_para,
                $corpo_html
            ));

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Configuração SMTP salva com sucesso!';
            $json['config_smtp_id'] = $config_smtp->getId();
        } catch (ConfigSmtpNaoEncontradaException | ConfigSmtpInvalidoException | UserException $e) {
            $json['erro'] = 'sucesso';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }
}