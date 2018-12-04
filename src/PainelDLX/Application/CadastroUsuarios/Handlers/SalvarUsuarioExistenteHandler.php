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

namespace PainelDLX\Application\CadastroUsuarios\Handlers;


use DLX\Contracts\CommandInterface;
use DLX\Contracts\Handlers\HandlerInterface;
use PainelDLX\Application\CadastroUsuarios\Exceptions\RegistroEntityNaoEncontradoException;
use PainelDLX\Domain\CadastroUsuarios\Entities\Usuario;
use PainelDLX\Domain\CadastroUsuarios\Services\VerificaEmailJaCadastrado;
use PainelDLX\Infra\ORM\Doctrine\Repositories\UsuarioRepository;

class SalvarUsuarioExistenteHandler implements HandlerInterface
{
    /**
     * @param CommandInterface $command
     * @throws \Exception
     */
    public function handle(CommandInterface $command)
    {
        try {
            /**
             * TODO: identificar o entity manager e obter o repository do usuário
             * @var UsuarioRepository $usuario_repository
             */
            $request = $command->getRequest();
            /** @var Usuario $usuario */
            $usuario = $usuario_repository->find($request['usuario_id']);

            if (!is_null($usuario)) {
                throw new RegistroEntityNaoEncontradoException('Usuário');
            }

            // Verifica se o email desse usuário não está sendo usado por outro usuário
            (new VerificaEmailJaCadastrado($usuario_repository, $usuario))->executar();
            $usuario_repository->update($usuario);

            return $usuario;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}