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

namespace PainelDLX\Presentation\Site\Controllers;

use DLX\Domain\Repositories\EntityRepositoryInterface;
use League\Tactician\CommandBus;
use Vilex\VileX;

abstract class SiteController
{
    /** @var VileX */
    protected $view;
    /** @var EntityRepositoryInterface */
    protected $repository;
    /** @var CommandBus */
    protected $command_bus;

    public function __construct(
        VileX $view,
        CommandBus $commandBus
    ) {
        $this->view = $view;
        $this->command_bus = $commandBus;

        // TODO: retirar a inclusão do tema do controller. Está aqui apenas para agilizar o dev
        $this->view->addArquivoCss(PAINEL_DLX . '/src/Presentation/Site/public/temas/painel-dlx/css/paineldlx.tema.css');
        $this->view->addArquivoJs(PAINEL_DLX . '/src/Presentation/Site/public/temas/painel-dlx/js/paineldlx.tema.js');
        $this->view->addArquivoJs(PAINEL_DLX . '/src/Presentation/Site/public/js/painel-dlx.js');
    }
}