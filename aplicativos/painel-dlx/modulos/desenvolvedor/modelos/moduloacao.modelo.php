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

namespace PainelDLX\Desenvolvedor\Modelos;

use Comum\DAO\ModuloAcao as ModuloAcaoDAO;
use DLX\Ajudantes\Visao as AjdVisao;
use DLX\Ajudantes\Sessao;
use Geral\Modelos\BaseModeloRegistro;
use Geral\Modelos\RegistroConsulta;
use Geral\Modelos\RegistroEdicao;

class ModuloAcao extends BaseModeloRegistro {
    use RegistroConsulta, RegistroEdicao, ModuloAcaoDAO;

    public function __construct($pk = null) {
        parent::__construct('dlx_paineldlx_modulos_acoes', 'acao_');
        $this->set__NomeModelo(AjdVisao::traduzirTexto('ação do módulo', 'painel-dlx'));
        $this->selecionarPK($pk);

        # Para registro que estão sendo adicionados, selecionar os grupos
        # marcados como 'Auto permissionamento'
        $sql = \DLX::$dlx->bd->query('SELECT grupo_usuario_id FROM dlx_paineldlx_grupos_usuarios WHERE grupo_usuario_autoperm = 1');
        $this->setGrupos($sql->fetchAll(\PDO::FETCH_COLUMN, 0));

        $this->adicionarEvento('antes', 'salvar', function () {
            // Remover o ID do grupo do usuário atual para impedir que ele adicione ou remova
            // permissionamento para ele mesmo
            $this->setGrupos(
                array_diff(
                    $this->getGrupos(),
                    [Sessao::dadoSessao('usuario_grupo', FILTER_VALIDATE_INT, 0)]
                )
            );
        });

        $this->adicionarEvento('depois', 'salvar', function () {
            # Atualizar as permissões
            if (!$this->reg_vazio) {
                $sql = \DLX::$dlx->bd->prepare('DELETE FROM dlx_paineldlx_permissoes WHERE permissao_acao = :pk');
                $sql->execute([':pk' => $this->getID()]);
            } // Fim if

            $sql = \DLX::$dlx->bd->prepare('INSERT INTO dlx_paineldlx_permissoes VALUES (:acao, :grupo)');

            foreach ($this->getGrupos() as $grupo) {
                $sql->execute([':acao' => $this->getID(), ':grupo' => $grupo]);
            } // Fim foreach
        });


        $this->adicionarEvento('depois', ['selecionarUK', 'selecionarPK'], function () {
            if (!$this->reg_vazio) {
                # Selecionar os grupos que possuem permissão para executar essa
                # ação
                $sql = \DLX::$dlx->bd->prepare('SELECT permissao_grupo FROM dlx_paineldlx_permissoes WHERE permissao_acao = :pk');
                $sql->execute([':pk' => $this->getID()]);
                $this->setGrupos($sql->fetchAll(\PDO::FETCH_COLUMN, 0));
            } // Fim if
        });
    } // Fim do método __construct
} // Fim do modelo ModuloAcao
