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

namespace PainelDLX\Admin\Controles;

use DLX\Ajudantes\ConstrutorSQL as AjdConstrutorSQL;
use DLX\Ajudantes\Sessao;
use Geral\Controles\PainelDLX;
use Geral\Controles\RegistroConsulta;
use Geral\Controles\RegistroEdicao;
use PainelDLX\Admin\Modelos\GrupoUsuario;
use PainelDLX\Desenvolvedor\Modelos\FormatoData;
use PainelDLX\Desenvolvedor\Modelos\Idioma;
use PainelDLX\Desenvolvedor\Modelos\Tema;

class Usuario extends PainelDLX {
    use RegistroConsulta, RegistroEdicao;

    public function __construct() {
        parent::__construct(dirname(__DIR__), new \PainelDLX\Admin\Modelos\Usuario(), 'admin/usuarios');

        $this->adicionarEvento('depois', 'mostrarDetalhes', function () {
            $grupo_usuario = new GrupoUsuario($this->modelo->getGrupo());
            $idioma = new Idioma($this->modelo->getIdioma());
            $tema = new Tema($this->modelo->getTema());
            $formato_data = new FormatoData($this->modelo->getFormatoData());

            # Visão
            if ($this->modelo->isBloqueado()) {
                $this->mostrarMensagemUsuario($this->visao->traduzir('Usuário bloqueado! Esse usuário não pode acessar o sistema.', 'painel-dlx'), '-atencao', 'html');
            } // Fim if

            $this->visao->adicionarTemplate('det_usuario');

            # Parâmetros
            $this->visao->tituloPagina($this->modelo->getNome());
            $this->visao->adicionarParam('conf:alterar-senha?', !$this->modelo->reg_vazio && (int)$this->modelo->getID() === Sessao::dadoSessao('usuario_id', FILTER_VALIDATE_INT, null));
            $this->visao->adicionarParam('conf:usuario-dominio?', $this->modelo->usuarioDominio());
            $this->visao->adicionarParam('info:grupo-usuario', $grupo_usuario->getNome());
            $this->visao->adicionarParam('info:idioma', $idioma->getNome());
            $this->visao->adicionarParam('info:tema', $tema->getNome());
            $this->visao->adicionarParam('info:formato-data', $formato_data->getDescr());

            $this->visao->mostrarConteudo();
        });
    } // Fim do método __construct


    /**
     * Mostrar a lista de registro
     * @return void
     */
    protected function mostrarLista() {
        $this->gerarLista(
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, 'usuario_id', $this->visao->traduzir('ID', 'painel-dlx')) . ', ' .
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, "CONCAT(usuario_nome, '<br/>', grupo_usuario_nome)", $this->visao->traduzir('Nome', 'painel-dlx')) . ', ' .
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, 'usuario_email', $this->visao->traduzir('Email', 'painel-dlx')) . ', ' .
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, 'usuario_login', $this->visao->traduzir('Login', 'painel-dlx')) . ', ' .
            sprintf(AjdConstrutorSQL::SQL_CASE_SIM_NAO, 'usuario_bloqueado', $this->visao->traduzir('Bloqueado?', 'painel-dlx')),
            ['order_by' => 'usuario_nome']
        );

        # Visão
        $this->visao->adicionarTemplate('comum/visoes/menu_opcoes');
        $this->visao->adicionarTemplate('comum/visoes/form_filtro');
        $this->visao->adicionarTemplate('comum/visoes/lista');

        # JS
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin-min.js');

        # Parâmetros
        $this->visao->tituloPagina($this->visao->traduzir('Usuários', 'painel-dlx'));
        $this->visao->adicionarParam('html:form-acao', $this->url_excluir);

        $this->visao->mostrarConteudo();
    } // Fim do método mostrarLista


    /**
     * Mostrar formulário de inclusão / edição do registro
     * @param  int $pk PK identificadora do registro. Quando não informada, o
     * formulário considerará que um novo registro será incluído.
     * @return void
     */
    protected function mostrarForm($pk = null) {
        $this->gerarForm('usuario', 'admin/usuarios/inserir', 'admin/usuarios/salvar', $pk);

        $grupo_usuario = new GrupoUsuario();
        $idioma = new Idioma();
        $tema = new Tema();
        $formato_data = new FormatoData();

        # Visão
        if ($this->modelo->isBloqueado()) {
            $this->mostrarMensagemUsuario($this->visao->traduzir('Usuário bloqueado! Esse usuário não pode acessar o sistema.', 'painel-dlx'), '-aviso', 'html');
        } // Fim if

        $this->visao->adicionarTemplate('comum/visoes/log_registro');
        $this->visao->adicionarTemplate('comum/visoes/menu_opcoes');
        $this->visao->adicionarTemplate('form_usuario');
        $this->visao->adicionarTemplate('comum/visoes/form_ajax');

        # JS
        $this->visao->adicionarJS('web/js/jquery-form-ajax/jquery.formajax.plugin-min.js');
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin.js');

        # Parâmetros
        $this->visao->adicionarParam('lista:grupos-usuarios',
            $grupo_usuario->carregarSelect((object)['where' => 'grupo_usuario_publicar = 1'], false, 'id', 'nome')
        );
        $this->visao->adicionarParam('lista:idiomas',
            $idioma->carregarSelect((object)['where' => 'idioma_publicar = 1'], false, 'id', 'nome')
        );
        $this->visao->adicionarParam('lista:temas', $tema->carregarSelect(false));
        $this->visao->adicionarParam('lista:formatos-data',
            $formato_data->carregarSelect((object)['where' => 'formato_data_publicar = 1'], false, 'id', 'descr')
        );

        # Configurações para montar o formulário
        $conf_editar_grupo = $this->modelo->reg_vazio || $this->modelo->getID() !== Sessao::dadoSessao('usuario_id', FILTER_VALIDATE_INT, null);

        $this->visao->adicionarParam('conf:editar-acesso?', $this->modelo->reg_vazio);
        $this->visao->adicionarParam('conf:editar-grupo?', $conf_editar_grupo);
        $this->visao->adicionarParam('conf:alterar-senha?', !$this->modelo->reg_vazio && (int)$this->modelo->getID() === Sessao::dadoSessao('usuario_id', FILTER_VALIDATE_INT, null));
        $this->visao->adicionarParam('conf:usuario-dominio?', $this->modelo->usuarioDominio());

        if ($conf_editar_grupo) {
            $grupo_usuario->selecionarPK($this->modelo->getGrupo());
            $this->visao->adicionarParam('info:nome-grupo', $grupo_usuario->getNome());
        } // Fim if

        $this->visao->mostrarConteudo();
    } // Fim do método mostrarForm

// Minha conta --------------------------------------------------------------------------------- //
    /**
     * Mostrar formulário de edição do usuário logado.
     * @return void
     */
    protected function formMinhaConta() {
        $this->mostrarForm(Sessao::dadoSessao('usuario_id', FILTER_VALIDATE_INT));
    } // Fim do método formMinhaConta

// Alterar senha ------------------------------------------------------------------------------- //
    /**
     * Mostrar o formulário para alterar a senha do usuário logado
     */
    protected function formAlterarSenha() {
        # Visão
        $this->visao->adicionarTemplate('form_alterar_senha');
        $this->visao->adicionarTemplate('comum/visoes/form_ajax');

        # JS
        $this->visao->adicionarJS('web/js/jquery-form-ajax/jquery.formajax.plugin-min.js');
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin-min.js');

        # Parâmetros
        $this->visao->tituloPagina($this->visao->traduzir('Alterar senha', 'painel-dlx'));
        $this->visao->adicionarParam('html:form-id', 'senha');

        $this->visao->mostrarConteudo();
    } // Fim do método formAlterarSenha

    /**
     * Receber as informações do formulário para alterar a senha do usuário logado
     */
    protected function alterarSenha() {
        $post = filter_input_array(INPUT_POST, [
            'senha_atual' => FILTER_DEFAULT, 
            'senha_nova' => FILTER_DEFAULT,
            'senha_nova_conf' => FILTER_DEFAULT
        ]);

        $this->modelo->alterarSenha($_SESSION['usuario_id'], $post['senha_atual'], $post['senha_nova'], $post['senha_nova_conf'])
            ? $this->mostrarMensagemUsuario($this->visao->traduzir('Senha alterada com sucesso!', 'painel-dlx'), '-sucesso')
            : $this->mostrarMensagemUsuario($this->visao->traduzir('A senha não pôde ser alterada. Tente novamente.', 'painel-dlx'), '-erro');
    } // Fim do método alterarSenha
}// Fim do controle Usuario
