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

namespace PainelDLX\Admin\Modelos;

use Comum\DTO\Usuario as UsuarioDTO;
use DLX\Ajudantes\Sessao;
use DLX\Ajudantes\Visao as AjdVisao;
use DLX\Excecao\DLX as DLXExcecao;
use Geral\Modelos\BaseModeloRegistro;
use Geral\Modelos\RegistroConsulta;
use Geral\Modelos\RegistroEdicao;
use PainelDLX\Admin\Modelos\GrupoUsuario;
use PainelDLX\Desenvolvedor\Modelos\FormatoData;
use PainelDLX\Desenvolvedor\Modelos\Idioma;
use PainelDLX\Desenvolvedor\Modelos\ModuloAcao;
use PainelDLX\Desenvolvedor\Modelos\ServidorDominio;


class Usuario extends BaseModeloRegistro {
    use RegistroConsulta, RegistroEdicao, UsuarioDTO;

    const EXPREG_USUARIO_DOMINIO = '~^([a-zA-Z]+(\.[a-zA-z]+)?)\\\{1}(.+)$~';

    private $root = [
        'login'     => 'root',
        'senha'     => 'b49e6b2b0215ed56edc7244c889dee3c',
        'sessao'    => [
            'usuario_id'            => -1,
            // 'usuario_grupo'         => -1,
            'grupo_usuario_nome'    => 'Super usuários',
            'usuario_nome'          => 'Super Usuário',
            'usuario_email'         => '',
            'usuario_login'         => 'root',
            'idioma_sigla'          => 'pt_BR',
            'usuario_tema'          => 'painel-dlx',
            'tema_pagina_mestra'    => 'padrao',
            'tema_nome'             => 'Painel-DL (DLX)',
            'tema_diretorio'        => 'painel-dlx',
            'tema_pagina_mestra'    => 'painel-dlx',
            'usuario_formato_data'  => 1,
            'formato_data_data'     => 'd/m/Y',
            'formato_data_hora'     => 'H:i',
            'formato_data_completo' => 'd/m/Y H:i',
            'usuario_bloqueado'     => false,
            'usuario_reset_senha'   => false
        ]
    ];

    public function __construct($pk = null) {
        parent::__construct('dlx_paineldlx_usuarios', 'usuario_');
        $this->set__NomeModelo(AjdVisao::traduzirTexto('usuário', 'painel-dlx'));
        $this->selecionarPK($pk);

        $this->bd_lista->join('dlx_paineldlx_grupos_usuarios', 'G', "(G.grupo_usuario_id = {$this->getBdPrefixo()}grupo)", 'INNER')
            ->join('dlx_paineldlx_idiomas', 'I', "(I.idioma_sigla = {$this->getBdPrefixo()}pref_idioma)", 'INNER')
            ->join('dlx_paineldlx_formatos_datas', 'D', "(D.formato_data_id = {$this->getBdPrefixo()}formato_data)", 'INNER');

        # Carregar preferências padrão
        $this->preferenciasPadrao();

        $this->adicionarEvento('antes', 'salvar', function () {
            $where_pk = $this->reg_vazio ? [] : ["{$this->getBdPrefixo()}id <> {$this->getID()}"];
            $where_email = ["{$this->getBdPrefixo()}email = '{$this->getEmail()}'"];

            # Verificar se o e-mail já está cadastrado
            if ($this->qtdeRegistros((object)['where' => array_merge($where_email, $where_pk)])) {
                throw new DLXExcecao(sprintf(AjdVisao::traduzirTexto('Outro usuário já está usando o e-mail <b>%s</p>!', 'painel-dlx'), $this->getEmail()), 403, '-info');
            } // Fim if

            # Verificar se as senhas informadas são iguais
            if ($this->reg_vazio && !$this->usuarioDominio() && $this->getSenha() !== $this->getConfSenha()) {
                throw new DLXExcecao(AjdVisao::traduzirTexto('As senhas informadas não coincidem.', 'painel-dlx'), 403, '-alerta');
            } // Fim if
        });


        $this->adicionarEvento('depois', 'salvar', function () {
            /*
             * Ao atualizar informações do usuário logado, também é necessário
             * atualizar os dados da sessão.
             */
            if (Sessao::dadoSessao('usuario_id', FILTER_VALIDATE_INT) === (int)$this->getID()) {
                $_SESSION = $this->listar(
                    (object)['where' => "{$this->getBdPrefixo()}login = '{$this->getLogin()}'"],
                    \DLX::$dlx->config('autenticacao', 'campos-login'),
                    0, 1, 0
                );
            } // Fim if
        });
    } // Fim do método __construct


    /**
     * Carregar as preferências padrão para novos usuários de acorodo com o que
     * foi configurado
     * @return void
     */
    public function preferenciasPadrao() {
        if ($this->reg_vazio) {
            // Selecionar o grupo padrão e vincular esse registro a ele por padrão
            $grupo_usuario = new GrupoUsuario();
            if ($grupo_usuario->selecionarPadrao()) {
                $this->setGrupo($grupo_usuario->getID());
            } // Fim if

            // Selecionar o idioma padrão e vincular esse registro a ele por padrão
            $idioma = new Idioma();
            if ($idioma->selecionarPadrao()) {
                $this->setPrefIdioma($idioma->getSigla());
            } // Fim if

            // Selecionar o formato de data padrão e vincular esse registro a ele por padrão
            $formato_data = new FormatoData();
            if ($formato_data->selecionarPadrao()) {
                $this->setFormatoData($formato_data->getID());
            } // Fim if
        } // Fim if
    } // Fim do método preferenciasPadrao


// Acesso ao sistema -------------------------------------------------------- //
    /**
     * Autenticar usuário.
     * Verificar se o par de informações nome de usuário e/ou e-mail e senha
     * coincidem com algum usuário do sistema
     * @param string $usuario Nome ou e-mail do usuário a ser autenticado
     * @param string $senha   Senha passada pelo usuário
     * @param bool   $md5     Define se a senha passada deverá ser criptografada
     * (true) ou já está criptografada e não é necessário nenhum tratamento nela
     * (false)
     * @throws DLXExcecao
     * @return array
     */
    public function autenticarUsuario($usuario, $senha, $md5 = true) {
        $usuario = filter_var($usuario, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);

        # Autenticação normal
        if (!$this->usuarioDominio($usuario)) {
            $senha = $md5 ? $this->criptografarSenha($senha) : filter_var($senha);

            # Login do super usuário
            if ($usuario === $this->root['login'] && $senha === $this->root['senha']) {
                $dados = $this->root['sessao'];
            } else {
                $dados = $this->listar(
                    (object)['where' => "({$this->getBdPrefixo()}login = '{$usuario}' OR {$this->getBdPrefixo()}email = '{$usuario}')" .
                        " AND {$this->getBdPrefixo()}senha = '{$senha}'"],
                    \DLX::$dlx->config('autenticacao', 'campos-login'),
                    0, 1, 0, false
                );
            } // Fim if ... else

        # Autenticação via domínio
        } else {
            $dados = $this->logonDominio($usuario, $senha);
        } // Fim if

        if (!(bool)$dados) {
            throw new DLXExcecao(AjdVisao::traduzirTexto('Usuário e / ou senha incorretos.', 'painel-dlx'), 403);
        } // Fim if

        return $dados;
    } // Fim do método autenticarUsuario


    /**
     * Verificar se o usuário tem permissão para executar uma determinada ação
     * @param  string $controle Controle a ser executado
     * @param  string $acao     Ação contida no controle a ser executada
     * @return bool             Retorna TRUE caso o usuário logado tenha a permissão
     * ou FALSE caso não tenha
     */
    public function verificarPerm($controle, $acao) {
        if ($this->reg_vazio) {
            return false;
        } // Fim if

        $controle = filter_var($controle);
        $acao = filter_var($acao);
        $modulo_acao = new ModuloAcao();
        $modulo_acao->bd_lista->join('dlx_paineldlx_permissoes', 'P', '(P.permissao_acao = acao_id)', 'INNER');

        return (bool)$modulo_acao->qtdeRegistros(
            (object)[
                'where' => [
                    "acao_classe = '{$controle}'",
                    // TODO: Adaptar esse trecho para funcionar no SQL Server
                    "FIND_IN_SET('{$acao}', acao_metodos)",
                    "P.permissao_grupo = {$this->getGrupo()}"
                ]
            ]
        );

        return (bool)$consulta['QTDE'];
    } // Fim do método verificarPerm


// Senhas ------------------------------------------------------------------- //
    /**
     * Criptografar a senha
     * @param  string $senha Senha a ser criptografada
     * @return string        Retorna a hash da senha criptografada ou a senha pura
     * caso nenhuma função para criptografia tenha sido informada
     */
    protected function criptografarSenha($senha) {
        $cripto_senha = \DLX::$dlx->config('autenticacao', 'cripto-senha');

        return !empty($cripto_senha) && is_callable($cripto_senha)
            ? call_user_func($cripto_senha, $senha)
            : $senha;
    } // Fim do método criptografarSenha

    /**
     * Alterar a senha do usuário
     *
     * @param int    $usuario               ID do usuário que fará a alteração da senha
     * @param string $senha_atual           Senha atual
     * @param string $senha_nova            Senha nova
     * @param string $senha_nova_confirm    Confirmação da senha nova
     * @param bool   $cripto                Define se as senhas devem passar pela função de criptografia
     * ou não
     *
     * @return bool Retorna TRUE caso a senha tenha sido alterada com sucesso ou false, caso tenha falhado
     * @throws DLXExcecao
     */
    protected function alterarSenha($usuario, $senha_atual, $senha_nova, $senha_nova_confirm, $cripto = true) {
        if ($cripto) {
            $senha_atual = $this->criptografarSenha($senha_atual);
        } // Fim if

        # Selecionar o usuário
        $this->selecionarPK(filter_var($usuario, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
            'flags'   => FILTER_NULL_ON_FAILURE
        ]));

        if (!$this->reg_vazio) {
            if ($this->usuarioDominio()) {
                throw new DLXExcecao(AjdVisao::traduzirTexto('Usuários de domínio não podem alterar a senha por esse sistema.<br/>Para alterar sua senha, por favor entre em contato com o administrador da sua rede.', 'painel-dl'), 1403);
            } // Fim if

            if ($this->getSenha() !== $senha_atual) {
                throw new DLXExcecao(AjdVisao::traduzirTexto('A senha atual informada está incorreta!', 'painel-dl'), 1043);
            } // Fim if

            if ($senha_nova !== $senha_nova_confirm) {
                throw new DLXExcecao(AjdVisao::traduzirTexto('As senhas informadas não coincidem!', 'painel-dl'), 1403);
            } // Fim if

            $this->setSenha($senha_nova, true);

            return $this->salvar(true, ['usuario_id', 'usuario_senha']);
        } // Fim if

        return false;
    } // Fim do método alterarSenha


// Domínio ------------------------------------------------------------------ //
    /**
     * Verificar se o usuário atual é um usuário de domínio
     * @param string|null $usuario Usuário a ser verificado. Quando não informado,
     * verifica o usuário selecionado no modelo
     * @return bool
     */
    public function usuarioDominio($usuario = null) {
        $testar_usuario = isset($usuario) ? $usuario : $this->login;
        return (bool)preg_match(static::EXPREG_USUARIO_DOMINIO, $testar_usuario);
    } // Fim do método usuarioDominio


    /**
     * Fazer logon no servidor de domínio
     * @param string $usuario Nome de usuário para logon
     * @param string $senha   Senha de logon no servidor de domínio
     * @return array|bool|null
     * @throws DLXExcecao
     */
    public function logonDominio($usuario, $senha) {
        /*
         * Primeiro preciso identificar o domínio para então selecionar suas
         * configurações
         */
        preg_match(static::EXPREG_USUARIO_DOMINIO, $usuario, $dominio);
        $dominio = $dominio[1];

        $servidor = new ServidorDominio();
        $servidor->selecionarUK(['nome' => $dominio]);

        $conexao_ldap = @ldap_connect($servidor->getServidor(), $servidor->getPorta());

        if (@ldap_bind($conexao_ldap, $usuario, $senha)) {
            # Fechar a conexão com o servidor
            ldap_close($conexao_ldap);

            return $this->listar(
                (object)['where' => "{$this->getBdPrefixo()}login = '{$usuario}'"],
                \DLX::$dlx->config('autenticacao', 'campos-login'),
                0, 1, 0
            );
        } // Fim if

        $msg_servidor = ldap_error($conexao_ldap);

        # Fechar a conexão com o servidor
        ldap_close($conexao_ldap);

        throw new DLXExcecao(sprintf(AjdVisao::traduzirTexto('Não foi possível fazer logon no domínio <b>%s</b>: %s.', 'painel-dl'), $servidor->getNome(), $msg_servidor), 1403);
    }  // Fim do método logonDominio

// Configurações do usuário ------------------------------------------------- //
    /**
     * Bloquear o acesso ao sistema para esse usuário
     * @return bool Retorna TRUE se o usuário foi bloqueado com sucesso ou FALSE,
     * se houve algum erro
     */
    protected function bloquearAcesso() {
        if (!$this->isBloqueado()) {
            $this->setBloqueado(true);
            return $this->salvar(true, ['usuario_id', 'usuario_bloqueado']);
        } // Fim if

        return true;
    } // Fim do método bloquearAcesso


    /**
     * Permitir o acesso ao sistema para esse usuário
     * @return bool Retorna TRUE se o usuário foi desbloqueado com sucesso ou FALSE,
     * se houve algum erro
     */
    protected function desbloquearAcesso() {
        if ($this->isBloqueado()) {
            $this->setBloqueado(false);
            return $this->salvar(true, ['usuario_id', 'usuario_bloqueado']);
        } // Fim if

        return true;
    } // Fim do método desbloquearAcesso
} // Fim do modelo Usuario
