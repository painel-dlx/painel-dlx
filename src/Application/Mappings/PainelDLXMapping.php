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

namespace PainelDLX\Application\Mappings;


use PainelDLX\UseCases\Emails\EditarConfigSmtp\EditarConfigSmtpCommand;
use PainelDLX\UseCases\Emails\EditarConfigSmtp\EditarConfigSmtpCommandHandler;
use PainelDLX\UseCases\Emails\ExcluirConfigSmtp\ExcluirConfigSmtpCommand;
use PainelDLX\UseCases\Emails\ExcluirConfigSmtp\ExcluirConfigSmtpCommandHandler;
use PainelDLX\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommand;
use PainelDLX\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommandHandler;
use PainelDLX\UseCases\Emails\GetListaConfigSmtp\GetListaConfigSmtpCommand;
use PainelDLX\UseCases\Emails\GetListaConfigSmtp\GetListaConfigSmtpCommandHandler;
use PainelDLX\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpCommand;
use PainelDLX\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpCommandHandler;
use PainelDLX\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpCommand;
use PainelDLX\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpCommandHandler;
use PainelDLX\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesCommand;
use PainelDLX\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesCommandHandler;
use PainelDLX\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioCommand;
use PainelDLX\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioCommandHandler;
use PainelDLX\UseCases\GruposUsuarios\ExcluirGrupoUsuario\ExcluirGrupoUsuarioCommand;
use PainelDLX\UseCases\GruposUsuarios\ExcluirGrupoUsuario\ExcluirGrupoUsuarioCommandHandler;
use PainelDLX\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommand;
use PainelDLX\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommandHandler;
use PainelDLX\UseCases\GruposUsuarios\GetListaGruposUsuarios\GetListaGruposUsuariosCommand;
use PainelDLX\UseCases\GruposUsuarios\GetListaGruposUsuarios\GetListaGruposUsuariosCommandHandler;
use PainelDLX\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioCommand;
use PainelDLX\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioCommandHandler;
use PainelDLX\UseCases\Home\GetListaWigets\GetListaWidgetsCommand;
use PainelDLX\UseCases\Home\GetListaWigets\GetListaWidgetsCommandHandler;
use PainelDLX\UseCases\ListaRegistros\ConverterFiltro2Criteria\ConverterFiltro2CriteriaCommand;
use PainelDLX\UseCases\ListaRegistros\ConverterFiltro2Criteria\ConverterFiltro2CriteriaCommandHandler;
use PainelDLX\UseCases\Login\FazerLogin\FazerLoginCommand;
use PainelDLX\UseCases\Login\FazerLogin\FazerLoginCommandHandler;
use PainelDLX\UseCases\Login\FazerLogout\FazerLogoutCommand;
use PainelDLX\UseCases\Login\FazerLogout\FazerLogoutCommandHandler;
use PainelDLX\UseCases\Modulos\GetListaMenu\GetListaMenuCommand;
use PainelDLX\UseCases\Modulos\GetListaMenu\GetListaMenuCommandHandler;
use PainelDLX\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario\CadastrarPermissaoUsuarioCommand;
use PainelDLX\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario\CadastrarPermissaoUsuarioCommandHandler;
use PainelDLX\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioCommand;
use PainelDLX\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioCommandHandler;
use PainelDLX\UseCases\PermissoesUsuario\ExcluirPermissaoUsuario\ExcluirPermissaoUsuarioCommand;
use PainelDLX\UseCases\PermissoesUsuario\ExcluirPermissaoUsuario\ExcluirPermissaoUsuarioCommandHandler;
use PainelDLX\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioCommand;
use PainelDLX\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioCommandHandler;
use PainelDLX\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommand;
use PainelDLX\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommandHandler;
use PainelDLX\UseCases\Usuarios\EditarUsuario\EditarUsuarioCommand;
use PainelDLX\UseCases\Usuarios\EditarUsuario\EditarUsuarioCommandHandler;
use PainelDLX\UseCases\Usuarios\EnviarEmailResetSenha\EnviarEmailResetSenhaCommand;
use PainelDLX\UseCases\Usuarios\EnviarEmailResetSenha\EnviarEmailResetSenhaCommandHandler;
use PainelDLX\UseCases\Usuarios\ExcluirUsuario\ExcluirUsuarioCommand;
use PainelDLX\UseCases\Usuarios\ExcluirUsuario\ExcluirUsuarioCommandHandler;
use PainelDLX\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommand;
use PainelDLX\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommandHandler;
use PainelDLX\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashCommand;
use PainelDLX\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashCommandHandler;
use PainelDLX\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommand;
use PainelDLX\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommandHandler;
use PainelDLX\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommand;
use PainelDLX\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommandHandler;
use PainelDLX\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommand;
use PainelDLX\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommandHandler;
use PainelDLX\UseCases\Usuarios\UtilizarResetSenha\UtilizarResetSenhaCommand;
use PainelDLX\UseCases\Usuarios\UtilizarResetSenha\UtilizarResetSenhaCommandHandler;
use PainelDLX\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId\GetPermissaoUsuarioPorIdCommand;
use PainelDLX\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId\GetPermissaoUsuarioPorIdCommandHandler;

class PainelDLXMapping
{
    private $mapping = [
        NovoUsuarioCommand::class => NovoUsuarioCommandHandler::class,
        EditarUsuarioCommand::class => EditarUsuarioCommandHandler::class,
        ExcluirUsuarioCommand::class => ExcluirUsuarioCommandHandler::class,
        NovoGrupoUsuarioCommand::class => NovoGrupoUsuarioCommandHandler::class,
        EditarGrupoUsuarioCommand::class => EditarGrupoUsuarioCommandHandler::class,
        ExcluirGrupoUsuarioCommand::class => ExcluirGrupoUsuarioCommandHandler::class,
        AlterarSenhaUsuarioCommand::class => AlterarSenhaUsuarioCommandHandler::class,
        CadastrarPermissaoUsuarioCommand::class => CadastrarPermissaoUsuarioCommandHandler::class,
        EditarPermissaoUsuarioCommand::class => EditarPermissaoUsuarioCommandHandler::class,
        ExcluirPermissaoUsuarioCommand::class => ExcluirPermissaoUsuarioCommandHandler::class,
        FazerLoginCommand::class => FazerLoginCommandHandler::class,
        FazerLogoutCommand::class => FazerLogoutCommandHandler::class,
        NovaConfigSmtpCommand::class => NovaConfigSmtpCommandHandler::class,
        GetConfigSmtpPorIdCommand::class => GetConfigSmtpPorIdCommandHandler::class,
        EditarConfigSmtpCommand::class => EditarConfigSmtpCommandHandler::class,
        ExcluirConfigSmtpCommand::class => ExcluirConfigSmtpCommandHandler::class,
        GetListaConfigSmtpCommand::class => GetListaConfigSmtpCommandHandler::class,
        SolicitarResetSenhaCommand::class => SolicitarResetSenhaCommandHandler::class,
        EnviarEmailResetSenhaCommand::class => EnviarEmailResetSenhaCommandHandler::class,
        GetResetSenhaPorHashCommand::class => GetResetSenhaPorHashCommandHandler::class,
        UtilizarResetSenhaCommand::class => UtilizarResetSenhaCommandHandler::class,
        GetGrupoUsuarioPorIdCommand::class => GetGrupoUsuarioPorIdCommandHandler::class,
        ConfigurarPermissoesCommand::class => ConfigurarPermissoesCommandHandler::class,
        GetListaPermissaoUsuarioCommand::class => GetListaPermissaoUsuarioCommandHandler::class,
        GetUsuarioPeloIdCommand::class => GetUsuarioPeloIdCommandHandler::class,
        TestarConfigSmtpCommand::class => TestarConfigSmtpCommandHandler::class,
        GetListaUsuariosCommand::class => GetListaUsuariosCommandHandler::class,
        GetListaGruposUsuariosCommand::class => GetListaGruposUsuariosCommandHandler::class,
        GetListaMenuCommand::class => GetListaMenuCommandHandler::class,
        GetListaWidgetsCommand::class => GetListaWidgetsCommandHandler::class,
        ConverterFiltro2CriteriaCommand::class => ConverterFiltro2CriteriaCommandHandler::class,
        GetPermissaoUsuarioPorIdCommand::class => GetPermissaoUsuarioPorIdCommandHandler::class,
    ];

    /**
     * @return array
     */
    public function getMapping(): array
    {
        return $this->mapping;
    }
}