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

namespace PainelDLX\Login\Controles;

use DLX\Ajudantes\Sessao;
use DLX\Ajudantes\Visao as AjdVisao;
use DLX\Classes\Email;
use DLX\Excecao\DLX as DLXExcecao;
use Geral\Controles\PainelDLX;
use Geral\Controles\RegistroEdicao;
use PainelDLX\Admin\Modelos\Usuario;
use PainelDLX\Desenvolvedor\Modelos\Tema;
use PainelDLX\Login\Modelos\Recuperacao;

class Login extends PainelDLX {
    use RegistroEdicao;

    /**
     * @var string SESSAO_TEMP
     * Identificador da sessão temporária para recuperação de senha
     */
    const SESSAO_TEMP = 'tmp-painel-dlx';

    public function __construct() {
        parent::__construct(dirname(__DIR__));
    }// Fim do método __construct


    /**
     * Mostrar formulário de login
     */
    public function mostrarForm() {
        # Carregar o tema
        $this->carregarTemaLogin();

        # Visão
        $this->visao->adicionarTemplate('form_login');

        # JS
        $this->visao->adicionarJS('web/js/jquery-form-ajax/jquery.formajax.plugin-min.js');
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin-min.js');
        $this->visao->adicionarJS('web/js/modulo-login-min.js');

        # Parâmetros
        $this->visao->tituloPagina($this->visao->traduzir('Acessar o sistema', 'painel-dlx'));

        $this->visao->mostrarConteudo();
    } // Fim do método mostrarForm


    /**
     * Acessar o sistema
     *
     * @param string|null $login Nome de usuário que está acessando o sistema (se não for informado, será usado o
     *                           _POST)
     * @param string|null $senha Senha do usuário que está acessando o sistema (se não for informado, será usado o
     *                           _POST)
     * @param bool        $md5   Informa se a senha que está sendo passada precisa ser criptografada. Ou seja, ainda é
     *                           a senha pura e não a hash
     *
     * @throws DLXExcecao
     */
    public function acessarSistema($login = null, $senha = null, $md5 = true) {
        $config_autent = \DLX::$dlx->config('autenticacao');

        if ((bool)$config_autent !== false) {
            $login = !isset($login) ? filter_input(INPUT_POST, 'usuario') : $login;
            $senha = !isset($senha) ? filter_input(INPUT_POST, 'senha') : $senha;

            $usuario = new Usuario();
            $dados = $usuario->autenticarUsuario($login, $senha, $md5);

            if ((int)$dados['usuario_bloqueado'] === 1) {
                throw new DLXExcecao($this->visao->traduzir('Esse usuário está bloqueado e não pode acessar o sistema nesse momento.<br/>Em caso de dúvidas, por favor contate o administrador do sistema.', 'painel-dlx'), 1403);
            } // Fim if

            if (Sessao::iniciarSessao($config_autent['nome'], "{$config_autent['prefixo']}{$dados['usuario_login']}")) {
                $_SESSION = $dados;
            } // Fim if

            # Alterar o idioma de acordo com a preferência do usuário
            \DLX::$dlx->carregarIdioma(Sessao::dadoSessao('idioma_sigla', FILTER_DEFAULT, \DLX::$dlx->config('aplicativo', 'idioma')));

            /*
             * Salvar a data atual de login
             * Essa data deve ser salva após carregar as informações na sessão para não sobrepôr a data anterior
             */
            $usuario->selecionarPK($dados['usuario_id']);

            /* TODO: Verificar necessidade de armazenar a data do último Login
            realizado
            if (!$usuario->reg_vazio) {
                $usuario->setUltimoLogin(date('Y-m-d H:i:s'));
                $usuario->salvar(true, ['usuario_id', 'usuario_ultimo_login']);
            } // Fim if */

            $this->mostrarMensagemUsuario($this->visao->traduzir('Você acessou o sistema!', 'painel-dlx'), '-sucesso');
        } // Fim if
    } // Fim do método acessarSistema


    /**
     * Encerrar a sessão do sistema e liberar todas os dados armazenados
     */
    public function encerrarSessao() {
        $sessao_nome = \DLX::$dlx->config('autenticacao', 'nome');
        Sessao::iniciarSessao($sessao_nome, null, true);
        Sessao::encerrarSessao();
        $this->mostrarMensagemUsuario($this->visao->traduzir('Você encerrou essa sessão!', 'painel-dlx'), '-sucesso');
    } // Fim do método encerrarSessao


// Recuperar senha ---------------------------------------------------------------------------------------------- //
    public function formEsqueciMinhaSenha() {
        # Carregar o tema
        $this->carregarTemaLogin();

        # Visão
        $this->visao->adicionarTemplate('form_esqueci_senha');

        # JS
        $this->visao->adicionarJS('web/js/jquery-form-ajax/jquery.formajax.plugin-min.js');
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin-min.js');
        $this->visao->adicionarJS('web/js/modulo-login-min.js');

        # Parâmetros
        $this->visao->tituloPagina($this->visao->traduzir('Esqueci minha senha', 'painel-dlx'));

        $this->visao->mostrarConteudo();
    } // Fim do método formEsqueciMinhaSenha


    /**
     * Enviar e-mail de recuperação de senha
     *
     * @throws DLXExcecao
     */
    public function recuperarSenha() {
        $login = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
        
        # Selecionar o ID do usuário
        $usuario = new Usuario();
        $infos = $usuario->listar((object)[
            'where' => "(usuario_login = '{$login}' OR usuario_email = '{$login}')"
        ], 'usuario_id, usuario_email', 0, 1, 0, false);
        
        if (count($infos) < 1) {
            throw new DLXExcecao($this->visao->traduzir('Usuário não localizado!', 'painel-dlx'), 1404);
        } // Fim if

        $recuperacao = new Recuperacao($infos['usuario_id']);
        
        if ($recuperacao->reg_vazio) {
            $recuperacao->setUsuario($infos['usuario_id']);
            $recuperacao->setHash(date('YmdHis'), true);
            $recuperacao->salvar();
        } // Fim if
        
        // Enviar o e-mail com o link para resetar a senha
        $link = AjdVisao::hostCompleto() . "login/resetar-senha/{$recuperacao->getHash()}";
        $assunto = $this->visao->traduzir('Recuperação de senha', 'painel-dlx');
        $corpo = sprintf($this->visao->traduzir('<p>Você solicitou a recuperação da sua senha.</p><p>Para recuperar a sua senha clique no link abaixo:</p><p><a href="%s" target="_blank">%s</a></p>', 'painel-dlx'), $link, $link);

        $email = new Email();
        $email->setGravarLogAuto(true);
        $envio = $email->enviar($infos['usuario_email'], $assunto, $corpo, __CLASS__);

        $envio
            ? $this->mostrarMensagemUsuario(sprintf($this->visao->traduzir('As instruções para recuperar a sua senha foram enviadas para <b>%s</b>.', 'painel-dlx'), $infos['usuario_email']), '-sucesso')
            : $this->mostrarMensagemUsuario(sprintf($this->visao->traduzir('Oh-ho! Aconteceu alguma coisa quando tentei enviar o e-mail:<br/>%s', 'painel-dlx'), $email->log_email->getStatus()), '-erro');
    } // Fim do método recuperarSenha


    /**
     * Formulário para resetar senha recuperada através do e-mail
     *
     * @param string $hash Hash de identificação da solicitação
     */
    public function formResetarSenha($hash) {
        # Carregar o tema
        $this->carregarTemaLogin();

        $recuperacao = new Recuperacao();
        $usuario = new Usuario();

        // Selecionar as informações da solcitação de recuperação e do usuário
        // que solicitou
        $recuperacao->selecionarUK([
            'hash' => filter_var($hash, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL)
        ]);
        $usuario->selecionarPK($recuperacao->getUsuario());

        // Armazenar o ID desse usuário em uma sessão temporária para que o script de reset
        // de senha possa identificar o usuário. O ID do usuário está sendo armazenado em sessão
        // por questão de segurança
        Sessao::iniciarSessao(self::SESSAO_TEMP);
        $_SESSION['usuario_id'] = $usuario->getID();

        # Visão
        $this->mostrarMensagemUsuario(sprintf('Se você <b>NÃO</b> é <span style="text-decoration:underline;font-weight:bold;text-transform:uppercase">%s</span> ou não solicitou essa recuperação de senha, feche essa página imediatamente e não continue com o procedimento.', $usuario->getNome()), '-aviso', 'html');
        $this->visao->adicionarTemplate('form_reset_senha');
            
        # JS
        $this->visao->adicionarJS('web/js/jquery-form-ajax/jquery.formajax.plugin-min.js');
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin-min.js');
        $this->visao->adicionarJS('web/js/modulo-login-min.js');

        # Parâmetros
        $this->visao->tituloPagina($this->visao->traduzir('Resetar senha', 'painel-dlx'));

        $this->visao->mostrarConteudo();
    } // Fim do método formResetarSenha


    /**
     * Resetar a senha do usuário
     */
    public function resetarSenha() {
        Sessao::iniciarSessao(self::SESSAO_TEMP, null, true);
        $usuario_id = Sessao::dadoSessao('usuario_id', FILTER_VALIDATE_INT);
        $usuario = new Usuario($usuario_id);
        
        $post = filter_input_array(INPUT_POST, [
            'senha_nova'      => FILTER_DEFAULT,
            'senha_nova_conf' => FILTER_DEFAULT
        ]);

        if (!$usuario->reg_vazio) {
            $usuario->alterarSenha($usuario_id, $usuario->getSenha(), $post['senha_nova'], $post['senha_nova_conf'], false);

            // Excluir a solicitação de recuperação de senha, para que a mesma
            // não seja mais utilizada
            $recuperacao = new Recuperacao($usuario_id);
            $recuperacao->excluir();

            Sessao::encerrarSessao();
            $this->mostrarMensagemUsuario($this->visao->traduzir('Senha alterada com sucesso!<br/>Você será redirecionado para a página de login.', 'painel-dlx'), '-sucesso');
        } else {
            $this->mostrarMensagemUsuario($this->visao->traduzir('Usuário não identificado!', 'painel-dlx'), '-erro');
        } // Fim if
    } // Fim do método resetSenha

// Visual -------------------------------------------------------------------------------------- //
    /**
     * Carregar o tema para exibir os formulário vinculados ao módulo de login.
     *
     * @return void
     */
    private function carregarTemaLogin() {
        $this->visao->setTema('painel-dlx');
        $this->visao->setPaginaMestra('login');
    } // Fim do método carregarTemaLogin
}
