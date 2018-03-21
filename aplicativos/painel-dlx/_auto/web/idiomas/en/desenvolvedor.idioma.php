<?php
/**
 * painel-dlx
 * @version: v1.17.08
 * @author: Diego Lepera
 *
 * Created by Diego Lepera on 2017-07-28. Please report any bug at
 * https://github.com/dlepera88-php/framework-dlx/issues
 *
 * The MIT License (MIT)
 * Copyright (c) 2017 Diego Lepera http://diegolepera.xyz/
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

use DLX\Ajudantes\Visao as AjdVisao;

$__DOMINIO = 'painel-dlx';

AjdVisao::adicionarTraducao('Idioma', 'Language', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('idioma', 'language', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Sigla', 'Initials', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Tema', 'Theme', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Data da criação', 'Created on', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Página mestra', 'Master page', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Diretório', 'Directory', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('formato de data', 'date format', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Formatos de datas', 'Date formats', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Formato de data e hora', 'Date and time format', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Formato da data', 'Date format', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Formato da hora', 'Time format', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Formato para exibição da data completa (data e hora).', 'Format to full display (date and time).', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Formato para exibição apenas da data.', 'Format to display only the date.', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Formato para exibição apenas da hora.', 'Format to display only the time.', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Define esse formato de data como padrão para inclusão de novos usuários.', 'Set this date format as default to include new users.', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('servidor de domínio', 'domain server', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Servidores de domínio', 'Domain servers', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Domínio', 'Domain', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Servidor', 'Server', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Servidores inativos não poderão fazer login no sistema.', 'Disabled servers cannot do logon on system.', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('IP ou hostname do servidor.', 'Server IP or hostname.', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('módulo', 'module', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Módulos', 'Modules', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Módulo', 'Module', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Aplicativo', 'Application', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Exibir no menu?', 'Show on menu?', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Nome do aplicativo do qual esse módulo faz parte.', 'Application name of this module.', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Este é um módulo principal', 'This is a parent module', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Módulo principal', 'Parent module', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('O módulo principal informado também é um sub-módulo. Por favor, informe um módulo principal válido e tente novamente.', 'The parent module typed is a submodule too. Please choose a valid parent module and try it again.', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Sub-módulos', 'Submodules', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('ação do módulo', 'module action', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Ações do módulo %s', '%s module actions', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('As ações devem ser incluídas apenas em submódulos.', 'The actions should be included only in the submodules.', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Classe', 'Class', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Métodos', 'Methods', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Informe os métodos envolvidos nessa ação.', 'Enter the methods involved in this action.', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Ações desse módulo', "Module's actions", IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Permissões', 'Permissions', IDIOMA_SIGLA, $__DOMINIO);
AjdVisao::adicionarTraducao('Nenhum <b>grupo de usuário</b> encontrado.', "No <b>user group</b> was found.", IDIOMA_SIGLA, $__DOMINIO);
