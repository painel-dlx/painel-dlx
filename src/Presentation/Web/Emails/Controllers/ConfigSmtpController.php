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
use PainelDLX\Application\Services\Exceptions\ErroAoEnviarEmailException;
use PainelDLX\Domain\Emails\Exceptions\ConfigSmtpNaoEncontradaException;
use PainelDLX\UseCases\Emails\ExcluirConfigSmtp\ExcluirConfigSmtpCommand;
use PainelDLX\UseCases\Emails\ExcluirConfigSmtp\ExcluirConfigSmtpCommandHandler;
use PainelDLX\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommand;
use PainelDLX\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommandHandler;
use PainelDLX\UseCases\Emails\GetListaConfigSmtp\GetListaConfigSmtpCommand;
use PainelDLX\UseCases\Emails\GetListaConfigSmtp\GetListaConfigSmtpCommandHandler;
use PainelDLX\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpCommand;
use PainelDLX\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpCommandHandler;
use PainelDLX\UseCases\ListaRegistros\ConverterFiltro2Criteria\ConverterFiltro2CriteriaCommand;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Presentation\Web\Common\Controllers\PainelDLXController;
use PainelDLX\UseCases\ListaRegistros\ConverterFiltro2Criteria\ConverterFiltro2CriteriaCommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\TemplateInvalidoException;
use Laminas\Diactoros\Response\JsonResponse;

/**
 * Class ConfigSmtpController
 * @package PainelDLX\Presentation\Web\Emails\Controllers
 * @covers ConfigSmtpControllerTest
 */
class ConfigSmtpController extends PainelDLXController
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     */
    public function detalheConfigSmtp(ServerRequestInterface $request): ResponseInterface
    {
        $config_smtp_id = filter_var($request->getQueryParams()['config_smtp_id'], FILTER_VALIDATE_INT);

        try {
            /** @var ConfigSmtp $config_smtp */
            /* @see GetConfigSmtpPorIdCommandHandler */
            $config_smtp = $this->command_bus->handle(new GetConfigSmtpPorIdCommand($config_smtp_id));

            // View
            $this->view->addTemplate('emails/det_config_smtp', [
                'config-smtp' => $config_smtp
            ]);

            // Parâmetros
            $this->view->setAtributo('titulo-pagina', "Configuração SMTP: {$config_smtp->getNome()}");
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
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     */
    public function listaConfigSmtp(ServerRequestInterface $request): ResponseInterface
    {
        $get = $request->getQueryParams();

        try {
            /** @var array $criteria */
            /* @see ConverterFiltro2CriteriaCommandHandler */
            $criteria = $this->command_bus->handle(new ConverterFiltro2CriteriaCommand($get['campos'], $get['busca']));

            /** @var array $lista_config_smtp */
            /* @see GetListaConfigSmtpCommandHandler */
            $lista_config_smtp = $this->command_bus->handle(new GetListaConfigSmtpCommand(
                $criteria,
                [],
                $get['qtde'],
                $get['offset']
            ));

            // Lista
            $this->view->addTemplate('emails/lista_config_smtp', [
                'lista-config-smtp' => $lista_config_smtp,
                'filtro' => $get
            ]);

            // Paginação
            $this->view->addTemplate('common/paginacao', [
                'pagina-atual' => $get['pg'],
                'qtde-registros-pagina' => $get['qtde'],
                'qtde-registros-lista' => count($lista_config_smtp)
            ]);

            // Parâmetros
            $this->view->setAtributo('titulo-pagina', 'Configurações SMTP');

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
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function excluirConfigSmtp(ServerRequestInterface $request): ResponseInterface
    {
        $config_smtp_id = filter_var($request->getParsedBody()['config_smtp_id'], FILTER_VALIDATE_INT);

        try {
            /** @var ConfigSmtp $config_smtp */
            /* @see GetConfigSmtpPorIdCommandHandler */
            $config_smtp = $this->command_bus->handle(new GetConfigSmtpPorIdCommand($config_smtp_id));

            /* @see ExcluirConfigSmtpCommandHandler */
            $this->command_bus->handle(new ExcluirConfigSmtpCommand($config_smtp));

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Configuração SMTP excluída com sucesso!';
        } catch (ConfigSmtpNaoEncontradaException | UserException $e) {
            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }

    /**
     * Enviar email de teste usando uma configuração SMTP específica
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function testarConfigSmtp(ServerRequestInterface $request): ResponseInterface
    {
        $post = filter_var_array($request->getParsedBody(), [
            'config_smtp_id' => FILTER_VALIDATE_INT,
            'servidor' => FILTER_SANITIZE_STRING,
            'porta' => FILTER_VALIDATE_INT,
            'requer_autent' => FILTER_VALIDATE_BOOLEAN,
            'conta' => FILTER_SANITIZE_STRING,
            'senha' => FILTER_SANITIZE_STRING,
            'cripto' => FILTER_SANITIZE_STRING,
            'de_nome' => FILTER_SANITIZE_STRING,
            'responder_para' => FILTER_VALIDATE_EMAIL,
            'corpo_html' => FILTER_VALIDATE_BOOLEAN
        ]);

        try {
            /** @var Usuario $usuario */
            $usuario = $this->session->get('usuario-logado');

            try {
                /** @var ConfigSmtp|null $config_smtp */
                /* @see GetConfigSmtpPorIdCommandHandler */
                $config_smtp = $this->command_bus->handle(new GetConfigSmtpPorIdCommand((int)$post['config_smtp_id']));
            } catch (ConfigSmtpNaoEncontradaException $e) {
                $config_smtp = null;
            }

            if (is_null($config_smtp)) {
                $config_smtp = new ConfigSmtp($post['servidor'], $post['porta']);
                $config_smtp
                    ->setRequerAutent((bool)$post['requer_autent'])
                    ->setConta($post['conta'])
                    ->setSenha($post['senha'])
                    ->setCripto($post['cripto'])
                    ->setDeNome($post['de_nome'])
                    ->setResponderPara($post['responder_para'])
                    ->setCorpoHtml((bool)$post['corpo_html']);
            }

            /* @see TestarConfigSmtpCommandHandler */
            $this->command_bus->handle(new TestarConfigSmtpCommand($config_smtp, $usuario->getEmail()));

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Email de teste enviado com sucesso!';
        } catch (ErroAoEnviarEmailException | UserException $e) {
            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }
}