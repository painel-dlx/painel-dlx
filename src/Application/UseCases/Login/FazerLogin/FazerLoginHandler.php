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

namespace PainelDLX\Application\UseCases\Login\FazerLogin;

use PainelDLX\Application\UseCases\Login\Exceptions\UsuarioOuSenhaInvalidosException;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use SechianeX\Contracts\SessionInterface;

class FazerLoginHandler
{
    /**
     * @var UsuarioRepositoryInterface
     */
    private $usuario_repository;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * FazerLoginHandler constructor.
     * @param UsuarioRepositoryInterface $usuario_repository
     * @param SessionInterface $session
     */
    public function __construct(
        UsuarioRepositoryInterface $usuario_repository,
        SessionInterface $session
    ) {
        $this->usuario_repository = $usuario_repository;
        $this->session = $session;
    }

    /**
     * @param FazerLoginCommand $command
     * @return Usuario|null
     * @throws UsuarioOuSenhaInvalidosException
     */
    public function handle(FazerLoginCommand $command)
    {
        $usuario = $this->usuario_repository->fazerLogin($command->getEmail(), $command->getSenha());

        if (!$usuario instanceof Usuario) {
            throw new UsuarioOuSenhaInvalidosException();
        }

        $this->session->set('logado', true);
        $this->session->set('usuario-logado', $usuario);
        
        return $usuario;
    }
}