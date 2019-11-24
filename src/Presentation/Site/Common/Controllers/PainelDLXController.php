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

namespace PainelDLX\Presentation\Site\Common\Controllers;

use DLX\Core\Configure;
use League\Tactician\CommandBus;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Vilex\VileX;

abstract class PainelDLXController
{
    /**
     * @var VileX
     */
    protected $view;
    /**
     * @var CommandBus
     */
    protected $command_bus;
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * PainelDLXController constructor.
     * @param VileX $view
     * @param CommandBus $commandBus
     * @param SessionInterface $session
     * @throws ViewNaoEncontradaException
     */
    public function __construct(
        VileX $view,
        CommandBus $commandBus,
        SessionInterface $session
    ) {
        $this->view = $view;
        $this->command_bus = $commandBus;
        $this->session = $session;

        // TODO: retirar a inclusão do tema do controller. Está aqui apenas para agilizar o dev
        $versao = Configure::get('app', 'versao');
        $this->view->addArquivoCss('/vendor/painel-dlx/ui-painel-dlx/css/painel-dlx.tema.css', false, $versao);
        $this->view->addArquivoJs('/vendor/painel-dlx/ui-painel-dlx/js/paineldlx.tema-min.js', false, $versao);
        $this->view->addArquivoJs('public/js/painel-dlx-min.js',false, $versao);

        $pagina_mestra = $session->get('vilex:pagina-mestra') ?: 'painel-dlx-master';

        $this->view->setPaginaMestra("public/views/paginas-mestras/{$pagina_mestra}.phtml");
        $this->view->setViewRoot('public/views/');
        $this->view->addTemplate('common/titulo_pagina');
        $this->view->addTemplate('common/rodape');
    }
}