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
use PainelDLX\Application\UseCases\Login\FazerLogin\FazerLoginCommand;
use PainelDLX\Application\UseCases\Login\FazerLogin\FazerLoginHandler;
use PainelDLX\Application\UseCases\Login\FazerLogout\FazerLogoutCommand;
use PainelDLX\Application\UseCases\Login\FazerLogout\FazerLogoutHandler;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Presentation\Site\Controllers\SiteController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\VileX;
use Zend\Diactoros\Response\JsonResponse;

class LoginController extends SiteController
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * LoginController constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     * @param SessionInterface $sessao
     */
    public function __construct(
        VileX $view,
        CommandBus $commandBus,
        SessionInterface $sessao
    ) {
        parent::__construct($view, $commandBus);

        $this->view->setPaginaMestra('src/Presentation/Site/public/views/painel-dlx-master.phtml');
        $this->view->setViewRoot('src/Presentation/Site/public/views/login');
        $this->session = $sessao;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     */
    public function formLogin(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array(
            $request->getQueryParams(),
            ['redirect-url' => FILTER_DEFAULT]
        );

        // JS
        $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js');

        // View
        $this->view->addTemplate('form_login', $get);
        return $this->view->render();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function fazerLogin(ServerRequestInterface $request): ResponseInterface
    {
        $get = filter_var_array(
            $request->getParsedBody(),
            [
                'email' => FILTER_VALIDATE_EMAIL,
                'senha' => FILTER_DEFAULT,
                'redirect-url' => FILTER_DEFAULT
            ]
        );

        /**
         * @var string $email
         * @var string $senha
         */
        extract($get); unset($get);

        try {
            /**
             * @covers FazerLoginHandler
             * @var Usuario|null $usuario
             */
            $usuario = $this->commandBus->handle(new FazerLoginCommand($email, $senha));

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = "Seja bem-vindo {$usuario->getNome()}!";
        } catch (UserException $e) {
            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function fazerLogout(ServerRequestInterface $request): ResponseInterface
    {
        try {
            /** @covers FazerLogoutHandler */
            $this->commandBus->handle(new FazerLogoutCommand());

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Sessão encerrada com sucesso!';
        } catch (UserException $e) {
            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }
}