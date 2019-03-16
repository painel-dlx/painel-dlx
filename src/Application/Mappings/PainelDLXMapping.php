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


use PainelDLX\Application\UseCases\Emails\EditarConfigSmtp\EditarConfigSmtpCommand;
use PainelDLX\Application\UseCases\Emails\EditarConfigSmtp\EditarConfigSmtpCommandHandler;
use PainelDLX\Application\UseCases\Emails\ExcluirConfigSmtp\ExcluirConfigSmtpCommand;
use PainelDLX\Application\UseCases\Emails\ExcluirConfigSmtp\ExcluirConfigSmtpCommandHandler;
use PainelDLX\Application\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommand;
use PainelDLX\Application\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommandHandler;
use PainelDLX\Application\UseCases\Emails\GetListaConfigSmtp\GetListaConfigSmtpCommand;
use PainelDLX\Application\UseCases\Emails\GetListaConfigSmtp\GetListaConfigSmtpCommandHandler;
use PainelDLX\Application\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpCommand;
use PainelDLX\Application\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpHandler;
use PainelDLX\Application\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpCommand;
use PainelDLX\Application\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesCommandHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\EditarGrupoUsuario\EditarGrupoUsuarioCommandHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\ExcluirGrupoUsuario\ExcluirGrupoUsuarioCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\ExcluirGrupoUsuario\ExcluirGrupoUsuarioCommandHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommandHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\GetListaGruposUsuarios\GetListaGruposUsuariosCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\GetListaGruposUsuarios\GetListaGruposUsuariosCommandHandler;
use PainelDLX\Application\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioCommand;
use PainelDLX\Application\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioCommandHandler;
use PainelDLX\Application\UseCases\Home\GetListaWigets\GetListaWidgetsCommand;
use PainelDLX\Application\UseCases\Home\GetListaWigets\GetListaWidgetsCommandHandler;
use PainelDLX\Application\UseCases\ListaRegistros\ConverterFiltro2Criteria\ConverterFiltro2CriteriaCommand;
use PainelDLX\Application\UseCases\ListaRegistros\ConverterFiltro2Criteria\ConverterFiltro2CriteriaCommandHandler;
use PainelDLX\Application\UseCases\Login\FazerLogin\FazerLoginCommand;
use PainelDLX\Application\UseCases\Login\FazerLogin\FazerLoginCommandHandler;
use PainelDLX\Application\UseCases\Login\FazerLogout\FazerLogoutCommand;
use PainelDLX\Application\UseCases\Login\FazerLogout\FazerLogoutCommandHandler;
use PainelDLX\Application\UseCases\Modulos\GetListaMenu\GetListaMenuCommand;
use PainelDLX\Application\UseCases\Modulos\GetListaMenu\GetListaMenuCommandHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario\CadastrarPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\CadastrarPermissaoUsuario\CadastrarPermissaoUsuarioCommandHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\EditarPermissaoUsuario\EditarPermissaoUsuarioCommandHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\ExcluirPermissaoUsuario\ExcluirPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\ExcluirPermissaoUsuario\ExcluirPermissaoUsuarioCommandHandler;
use PainelDLX\Application\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioCommand;
use PainelDLX\Application\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioCommandHandler;
use PainelDLX\Application\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\AlterarSenhaUsuario\AlterarSenhaUsuarioCommandHandler;
use PainelDLX\Application\UseCases\Usuarios\EditarUsuario\EditarUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\EditarUsuario\EditarUsuarioCommandHandler;
use PainelDLX\Application\UseCases\Usuarios\EnviarEmailResetSenha\EnviarEmailResetSenhaCommand;
use PainelDLX\Application\UseCases\Usuarios\EnviarEmailResetSenha\EnviarEmailResetSenhaCommandHandler;
use PainelDLX\Application\UseCases\Usuarios\ExcluirUsuario\ExcluirUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\ExcluirUsuario\ExcluirUsuarioCommandHandler;
use PainelDLX\Application\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommand;
use PainelDLX\Application\UseCases\Usuarios\GetListaUsuarios\GetListaUsuariosCommandHandler;
use PainelDLX\Application\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashCommand;
use PainelDLX\Application\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashCommandHandler;
use PainelDLX\Application\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommand;
use PainelDLX\Application\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommandHandler;
use PainelDLX\Application\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommand;
use PainelDLX\Application\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommandHandler;
use PainelDLX\Application\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommand;
use PainelDLX\Application\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommandHandler;
use PainelDLX\Application\UseCases\Usuarios\UtilizarResetSenha\UtilizarResetSenhaCommand;
use PainelDLX\Application\UseCases\Usuarios\UtilizarResetSenha\UtilizarResetSenhaCommandHandler;

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
        NovaConfigSmtpCommand::class => NovaConfigSmtpHandler::class,
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
        TestarConfigSmtpCommand::class => TestarConfigSmtpHandler::class,
        GetListaUsuariosCommand::class => GetListaUsuariosCommandHandler::class,
        GetListaGruposUsuariosCommand::class => GetListaGruposUsuariosCommandHandler::class,
        GetListaMenuCommand::class => GetListaMenuCommandHandler::class,
        GetListaWidgetsCommand::class => GetListaWidgetsCommandHandler::class,
        ConverterFiltro2CriteriaCommand::class => ConverterFiltro2CriteriaCommandHandler::class,
    ];

    /**
     * @return array
     */
    public function getMapping(): array
    {
        return $this->mapping;
    }
}