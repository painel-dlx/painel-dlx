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

namespace PainelDLX\Presentation\Web\Usuarios\Controllers;


use DLX\Core\Exceptions\UserException;
use PainelDLX\UseCases\Login\Exceptions\UsuarioOuSenhaInvalidosException;
use PainelDLX\UseCases\Login\FazerLogin\FazerLoginCommand;
use PainelDLX\UseCases\Login\FazerLogin\FazerLoginCommandHandler;
use PainelDLX\UseCases\Login\FazerLogout\FazerLogoutCommand;
use PainelDLX\UseCases\Login\FazerLogout\FazerLogoutCommandHandler;
use PainelDLX\UseCases\Modulos\GetListaMenu\GetListaMenuCommand;
use PainelDLX\UseCases\Modulos\GetListaMenu\GetListaMenuCommandHandler;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Presentation\Web\Common\Controllers\PainelDLXController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\TemplateInvalidoException;
use Laminas\Diactoros\Response\JsonResponse;

/**
 * Class LoginController
 * @package PainelDLX\Presentation\Web\Usuarios\Controllers
 * @covers LoginControllerTest
 */
class LoginController extends PainelDLXController
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     */
    public function formLogin(ServerRequestInterface $request): ResponseInterface
    {
        // JS
        $this->view->addArquivoJS('/vendor/dlepera88-jquery/jquery-form-ajax/jquery.formajax.plugin-min.js', false, VERSAO_PAINEL_DLX);

        // View
        $this->view->addTemplate('login/form_login');

        // Atributos
        $this->view->setAtributo('titulo-pagina', 'Login');

        return $this->view->render();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function fazerLogin(ServerRequestInterface $request): ResponseInterface
    {
        $post = filter_var_array($request->getParsedBody(), [
            'email' => FILTER_VALIDATE_EMAIL,
            'senha' => FILTER_DEFAULT
        ]);

        try {
            /* @var Usuario|null $usuario */
            /** @see FazerLoginCommandHandler */
            $usuario = $this->command_bus->handle(new FazerLoginCommand($post['email'], $post['senha']));

            /* @see GetListaMenuCommandHandler */
            $menu = $this->command_bus->handle(new GetListaMenuCommand($usuario));
            $this->session->set('html:lista-menu', $menu);

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = "Seja bem-vindo {$usuario->getNome()}!";
        } catch (UsuarioOuSenhaInvalidosException | UserException $e) {
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
            /* @see FazerLogoutCommandHandler */
            $this->command_bus->handle(new FazerLogoutCommand());

            $json['retorno'] = 'sucesso';
            $json['mensagem'] = 'Sessão encerrada com sucesso!';
        } catch (UserException $e) {
            $json['retorno'] = 'erro';
            $json['mensagem'] = $e->getMessage();
        }

        return new JsonResponse($json);
    }
}