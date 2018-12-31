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

use PainelDLX\Application\CadastroUsuarios\Commands\AlterarSenhaUsuarioCommand;
use PainelDLX\Application\CadastroUsuarios\Commands\NovoGrupoUsuarioCommand;
use PainelDLX\Application\CadastroUsuarios\Handlers\AlterarSenhaUsuarioHandler;
use PainelDLX\Application\CadastroUsuarios\Handlers\NovoGrupoUsuarioHandler;
use PainelDLX\Application\CadastroUsuarios\Commands\EditarGrupoUsuarioCommand;
use PainelDLX\Application\CadastroUsuarios\Handlers\EditarGrupoUsuarioHandler;
use PainelDLX\Application\CadastroUsuarios\Commands\ExcluirGrupoUsuarioCommand;
use PainelDLX\Application\CadastroUsuarios\Handlers\ExcluirGrupoUsuarioHandler;
use PainelDLX\Application\CadastroUsuarios\Commands\NovoUsuarioCommand;
use PainelDLX\Application\CadastroUsuarios\Handlers\NovoUsuarioHandler;
use PainelDLX\Application\CadastroUsuarios\Commands\EditarUsuarioCommand;
use PainelDLX\Application\CadastroUsuarios\Handlers\EditarUsuarioHandler;
use PainelDLX\Application\CadastroUsuarios\Commands\ExcluirUsuarioCommand;
use PainelDLX\Application\CadastroUsuarios\Handlers\ExcluirUsuarioHandler;
use PainelDLX\Application\CadastroUsuarios\Commands\CadastrarPermissaoUsuarioCommand;
use PainelDLX\Application\CadastroUsuarios\Handlers\CadastrarPermissaoUsuarioHandler;
use PainelDLX\Application\CadastroUsuarios\Commands\EditarPermissaoUsuarioCommand;
use PainelDLX\Application\CadastroUsuarios\Handlers\EditarPermissaoUsuarioHandler;
use PainelDLX\Application\CadastroUsuarios\Commands\ExcluirPermissaoUsuarioCommand;
use PainelDLX\Application\CadastroUsuarios\Handlers\ExcluirPermissaoUsuarioHandler;

return [
    NovoUsuarioCommand::class => NovoUsuarioHandler::class,
    EditarUsuarioCommand::class => EditarUsuarioHandler::class,
    ExcluirUsuarioCommand::class => ExcluirUsuarioHandler::class,
    NovoGrupoUsuarioCommand::class => NovoGrupoUsuarioHandler::class,
    EditarGrupoUsuarioCommand::class => EditarGrupoUsuarioHandler::class,
    ExcluirGrupoUsuarioCommand::class => ExcluirGrupoUsuarioHandler::class,
    AlterarSenhaUsuarioCommand::class => AlterarSenhaUsuarioHandler::class,
    CadastrarPermissaoUsuarioCommand::class => CadastrarPermissaoUsuarioHandler::class,
    EditarPermissaoUsuarioCommand::class => EditarPermissaoUsuarioHandler::class,
    ExcluirPermissaoUsuarioCommand::class => ExcluirPermissaoUsuarioHandler::class,
];