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


use PainelDLX\Application\UseCases\Emails\EditarConfigSmtp\EditarConfigSmtpCommand;
use PainelDLX\Application\UseCases\Emails\EditarConfigSmtp\EditarConfigSmtpHandler;
use PainelDLX\Application\UseCases\Emails\ExcluirConfigSmtp\ExcluirConfigSmtpCommand;
use PainelDLX\Application\UseCases\Emails\ExcluirConfigSmtp\ExcluirConfigSmtpHandler;
use PainelDLX\Application\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommand;
use PainelDLX\Application\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdHandler;
use PainelDLX\Application\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpCommand;
use PainelDLX\Application\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\ExcluirGrupoUsuario\ExcluirGrupoUsuarioCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\ExcluirGrupoUsuario\ExcluirGrupoUsuarioHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioHandler;
use PainelDLX\Application\UseCases\Login\FazerLogin\FazerLoginCommand;
use PainelDLX\Application\UseCases\Login\FazerLogin\FazerLoginHandler;
use PainelDLX\Application\UseCases\Login\FazerLogout\FazerLogoutCommand;
use PainelDLX\Application\UseCases\Login\FazerLogout\FazerLogoutHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario\CadastrarPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario\CadastrarPermissaoUsuarioHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\ExcluirPermissaoUsuario\ExcluirPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\ExcluirPermissaoUsuario\ExcluirPermissaoUsuarioHandler;
use PainelDLX\Application\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioHandler;
use PainelDLX\Application\UseCases\Usuarios\EditarUsuario\EditarUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\EditarUsuario\EditarUsuarioHandler;
use PainelDLX\Application\UseCases\Usuarios\ExcluirUsuario\ExcluirUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\ExcluirUsuario\ExcluirUsuarioHandler;
use PainelDLX\Application\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\NovoUsuario\NovoUsuarioHandler;

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
    FazerLoginCommand::class => FazerLoginHandler::class,
    FazerLogoutCommand::class => FazerLogoutHandler::class,
    NovaConfigSmtpCommand::class => NovaConfigSmtpHandler::class,
    GetConfigSmtpPorIdCommand::class => GetConfigSmtpPorIdHandler::class,
    EditarConfigSmtpCommand::class => EditarConfigSmtpHandler::class,
    ExcluirConfigSmtpCommand::class => ExcluirConfigSmtpHandler::class,
];