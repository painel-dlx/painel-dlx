<?php

/**
 * @Autor      : Diego Lepera
 * @E-mail     : d_lepera@hotmail.com
 * @Projeto    : FrameworkDL
 * @Data       : 12/01/2015 10:27:37
 */

namespace PainelDLX\Login\Modelos;

use Comum\DAO\Recuperacao as RecuperacaoDAO;
use Geral\Modelos\BaseModeloRegistro;
use Geral\Modelos\RegistroEdicao;

class Recuperacao extends BaseModeloRegistro {
    use RegistroEdicao, RecuperacaoDAO;

    /**
     * Recuperacao constructor.
     * @param int|null $pk Valor da PK do registro a ser selecionado automaticamente
     */
    public function __construct($pk = null) {
        parent::__construct('dlx_paineldlx_usuarios_recuperacoes', 'recuperacao_');
        $this->selecionarPK($pk);

        // Ativar a inserção de valores na PK, pois a PK dessa tabela deverá receber o
        // ID do usuário que está solicitando a recuperação da senha
        $this->insert_pk = true;
    } // Fim do método __construct
} // Fim do Modelo Recuperacao
