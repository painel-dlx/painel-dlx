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
use DLX\Contracts\HandlerInterface;
use PainelDLX\Application\CadastroUsuarios\Commands\CadastrarNovoUsuarioCommand;
use PainelDLX\Domain\CadastroUsuarios\Entities\Usuario;
use PainelDLX\Domain\CadastroUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\CadastroUsuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Domain\CadastroUsuarios\Services\VerificaEmailJaCadastrado;
use PainelDLX\Domain\CadastroUsuarios\Services\VerificaSenhasIguais;
use PainelDLX\Domain\CadastroUsuarios\ValueObjects\SenhaUsuario;

class CadastrarNovoUsuarioHandler implements HandlerInterface
{
    /** @var UsuarioRepositoryInterface */
    private $usuario_repository;
    /** @var GrupoUsuarioRepositoryInterface */
    private $grupo_usuario_repository;

    /**
     * CadastrarNovoUsuarioHandler constructor.
     * @param UsuarioRepositoryInterface $usuario_repository
     * @param GrupoUsuarioRepositoryInterface $grupo_usuario_repository
     */
    public function __construct(
        UsuarioRepositoryInterface $usuario_repository,
        GrupoUsuarioRepositoryInterface $grupo_usuario_repository
    ) {
        $this->usuario_repository = $usuario_repository;
        $this->grupo_usuario_repository = $grupo_usuario_repository;
    }

    /**
     * @param CommandInterface $command
     * @throws \Exception
     */
    public function handle(CommandInterface $command)
    {
        /** @var CadastrarNovoUsuarioCommand $command */

        try {
            $lista_grupos = $this->grupo_usuario_repository->getListaGruposByIds(...$command->getGrupos());
            $usuario = Usuario::create($command->getNome(), $command->getEmail(), ...$lista_grupos);
            $usuario->setSenha($command->getSenha());

            // Verificar se o email está cadastrado para outro usuário
            (new VerificaEmailJaCadastrado($this->usuario_repository, $usuario))->executar();
            (new VerificaSenhasIguais($usuario, new SenhaUsuario($command->getSenha(), $command->getSenhaConfirm())))->executar();
            $this->usuario_repository->create($usuario);

            return $usuario;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}