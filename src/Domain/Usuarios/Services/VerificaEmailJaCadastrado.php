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

namespace PainelDLX\Domain\Usuarios\Services;


use DLX\Contracts\ServiceInterface;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Exceptions\EmailUtilizadoPorOutroUsuarioException;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;

class VerificaEmailJaCadastrado implements ServiceInterface
{
    /** @var UsuarioRepositoryInterface */
    private $usuario_repository;
    /** @var Usuario */
    private $usuario;

    public function __construct(
        UsuarioRepositoryInterface $usuarioRepository,
        Usuario $usuario
    ) {
        $this->usuario_repository = $usuarioRepository;
        $this->usuario = $usuario;
    }

    /**
     * Executa a verificação se o email do usuário informado já está sendo usado por outro usuario.
     * @return bool
     * @throws EmailUtilizadoPorOutroUsuarioException
     */
    public function executar(): bool
    {
        $is_email_utilizado = $this->usuario_repository->hasOutroUsuarioComMesmoEmail($this->usuario);

        if ($is_email_utilizado) {
            throw new EmailUtilizadoPorOutroUsuarioException($this->usuario->getEmail());
        }

        return false;
    }
}