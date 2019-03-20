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


use PainelDLX\Application\Services\PainelDLX;
use RautereX\RautereX;
use SechianeX\Contracts\SessionInterface;

abstract class PainelDLXRouter
{
    /**
     * @var RautereX
     */
    private $router;
    /**
     * @var PainelDLX
     */
    protected $painel_dlx;
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * PainelDLXRouter constructor.
     * @param RautereX $router
     * @param PainelDLX $painel_dlx
     * @param SessionInterface $session
     * @todo desacoplar RautereX e SechianeX
     */
    public function __construct(
        RautereX $router,
        PainelDLX $painel_dlx,
        SessionInterface $session
    ) {
        $this->router = $router;
        $this->painel_dlx = $painel_dlx;
        $this->session = $session;
    }

    /**
     * @return RautereX
     */
    public function getRouter(): RautereX
    {
        return $this->router;
    }

    /**
     * Registrar todas as rotas
     */
    abstract public function registrar(): void;
}