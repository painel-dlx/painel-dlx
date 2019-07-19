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

namespace PainelDLX\Application\Routes;


use PainelDLX\Application\Middlewares\CriptografarSenhas;
use PainelDLX\Application\Middlewares\DefinePaginaMestra;
use PainelDLX\Application\Middlewares\VerificarLogon;
use PainelDLX\Application\Services\PainelDLX;
use PainelDLX\Presentation\Site\Usuarios\Controllers\LoginController;
use PainelDLX\Presentation\Site\Usuarios\Controllers\ResetSenhaController;

class LoginRouter extends PainelDLXRouter
{
    /**
     * Registrar todas as rotas
     */
    public function registrar(): void
    {
        $router = $this->getRouter();
        $container = PainelDLX::getInstance()->getContainer();

        /** @var VerificarLogon $verificar_logon */
        $verificar_logon = $container->get(VerificarLogon::class);
        /** @var DefinePaginaMestra $define_pagina_mestra */
        $define_pagina_mestra = $container->get(DefinePaginaMestra::class);

        $router->get(
            '/painel-dlx/login',
            [LoginController::class, 'formLogin']
        )->middlewares(
            $define_pagina_mestra
        );

        $router->post(
            '/painel-dlx/login/fazer-login',
            [LoginController::class, 'fazerLogin']
        )->middlewares(new CriptografarSenhas('senha'));

        $router->get(
            '/painel-dlx/login/encerrar-sessao',
            [LoginController::class, 'fazerLogout']
        )->middlewares($verificar_logon);

        $router->get(
            '/painel-dlx/recuperar-senha',
            [ResetSenhaController::class, 'formSolicitarResetSenha']
        )->middlewares(
            $define_pagina_mestra
        );

        $router->post(
            '/painel-dlx/reset-senha/solicitar',
            [ResetSenhaController::class, 'solicitarResetSenha']
        );

        $router->get(
            '/painel-dlx/recuperar-minha-senha',
            [ResetSenhaController::class, 'formResetSenha']
        )->middlewares(
            $define_pagina_mestra
        );

        $router->post(
            '/painel-dlx/recuperar-minha-senha',
            [ResetSenhaController::class, 'resetarSenha']
        )->middlewares(
            new CriptografarSenhas('senha_nova', 'senha_confirm')
        );
    }
}