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
use PainelDLX\Application\UseCases\Emails\GetListaConfigSmtp\GetListaConfigSmtpCommand;
use PainelDLX\Application\UseCases\Emails\GetListaConfigSmtp\GetListaConfigSmtpHandler;
use PainelDLX\Application\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpCommand;
use PainelDLX\Application\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\ExcluirGrupoUsuario\ExcluirGrupoUsuarioCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\ExcluirGrupoUsuario\ExcluirGrupoUsuarioHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioHandler;
use PainelDLX\Application\UseCases\Home\GetListaWigets\GetListaWidgetsCommand;
use PainelDLX\Application\UseCases\Home\GetListaWigets\GetListaWidgetsHandler;
use PainelDLX\Application\UseCases\Login\FazerLogin\FazerLoginCommand;
use PainelDLX\Application\UseCases\Login\FazerLogin\FazerLoginHandler;
use PainelDLX\Application\UseCases\Login\FazerLogout\FazerLogoutCommand;
use PainelDLX\Application\UseCases\Login\FazerLogout\FazerLogoutHandler;
use PainelDLX\Application\UseCases\Modulos\GetListaMenu\GetListaMenuCommand;
use PainelDLX\Application\UseCases\Modulos\GetListaMenu\GetListaMenuHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario\CadastrarPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario\CadastrarPermissaoUsuarioHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\ExcluirPermissaoUsuario\ExcluirPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\ExcluirPermissaoUsuario\ExcluirPermissaoUsuarioHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioHandler;
use PainelDLX\Application\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioHandler;
use PainelDLX\Application\UseCases\Usuarios\EditarUsuario\EditarUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\EditarUsuario\EditarUsuarioHandler;
use PainelDLX\Application\UseCases\Usuarios\EnviarEmailResetSenha\EnviarEmailResetSenhaCommand;
use PainelDLX\Application\UseCases\Usuarios\EnviarEmailResetSenha\EnviarEmailResetSenhaHandler;
use PainelDLX\Application\UseCases\Usuarios\ExcluirUsuario\ExcluirUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\ExcluirUsuario\ExcluirUsuarioHandler;
use PainelDLX\Application\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommand;
use PainelDLX\Application\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosHandler;
use PainelDLX\Application\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashCommand;
use PainelDLX\Application\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashHandler;
use PainelDLX\Application\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommand;
use PainelDLX\Application\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdHandler;
use PainelDLX\Application\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\NovoUsuario\NovoUsuarioHandler;
use PainelDLX\Application\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommand;
use PainelDLX\Application\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaHandler;
use PainelDLX\Application\UseCases\Usuarios\UtilizarResetSenha\UtilizarResetSenhaCommand;
use PainelDLX\Application\UseCases\Usuarios\UtilizarResetSenha\UtilizarResetSenhaHandler;
use PainelDLX\Application\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpCommand;
use PainelDLX\Application\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpHandler;

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
    GetListaConfigSmtpCommand::class => GetListaConfigSmtpHandler::class,
    SolicitarResetSenhaCommand::class => SolicitarResetSenhaHandler::class,
    EnviarEmailResetSenhaCommand::class => EnviarEmailResetSenhaHandler::class,
    GetResetSenhaPorHashCommand::class => GetResetSenhaPorHashHandler::class,
    UtilizarResetSenhaCommand::class => UtilizarResetSenhaHandler::class,
    GetGrupoUsuarioPorIdCommand::class => GetGrupoUsuarioPorIdHandler::class,
    ConfigurarPermissoesCommand::class => ConfigurarPermissoesHandler::class,
    GetListaPermissaoUsuarioCommand::class => GetListaPermissaoUsuarioHandler::class,
    GetUsuarioPeloIdCommand::class => GetUsuarioPeloIdHandler::class,
    TestarConfigSmtpCommand::class => TestarConfigSmtpHandler::class,
    GetListaUsuariosCommand::class => GetListaUsuariosHandler::class,
    GetListaMenuCommand::class => GetListaMenuHandler::class,
    GetListaWidgetsCommand::class => GetListaWidgetsHandler::class,
];