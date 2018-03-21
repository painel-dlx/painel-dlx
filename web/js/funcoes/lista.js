/**
 * Funções referentes a listas de registros
 */


var listaRegistros = {
    /**
     * Identificar o formulário vinculado a lista de registros.
     * 
     * @returns {Object.jQuery} Retorna uma instância jQuery do formulário a ser
     * modificado.
     */
    identificarForm: function (DOM) {
        var $DOM = $(DOM), $form;

        if (DOM.tagName === 'FORM') {
            $form = $DOM;
        } else if ($DOM.parents('form').length > 0) {
            $form = $DOM.parents('form');
        } else {
            $form = $('form');
        } // Fim if

        return $form;
    },

    /**
     * Excluir as linhas selecionadas de uma lista de registros.
     * 
     * @param {Object.jQuery} $form Instância jQuery do formulário a ser considerado
     * para as alterações.
     * 
     * @returns {Void}
     */
    excluirLinhasSelecionadas: function ($form) {
        $form = $form || listaRegistros.identificarForm(this);
    
        // Identificar todos as linhas 'selecionadas' no formulário
        $form.find(':checkbox:checked').each(function (){
            $(this).parents('tr').remove();
        });
    },

    /**
     * Marcar ou desmarcar uma linha em uma lista de registro de acordo com o ID.
     * 
     * @param {Array|Int} id ID ou array com todos os IDs a serem marcados ou desmarcados
     * @param {Boolean} marcar Define se a linha / checkbox deverá ser marcada (TRUE) ou
     * desmarcada (FALSE). Padrão: TRUE
     * @param {Object.jQuery} $form Instância jQuery do formulário a ser considerado
     * para as alterações.
     * 
     * @returns {Void}
     */
    marcarLinhasID: function (id, marcar, $form) {
        if (typeof id === 'object') {
            var qtde = id.length;
            for (var i = 0; i < qtde; i++) {
                listaRegistros.marcarLinhasID(id[i], marcar);
            } // Fim for
        } else {
            marcar = typeof marcar === 'undefined' ? true : marcar;
            $form = $form || listaRegistros.identificarForm(this);

            if ($form.length > 0) {
                $form.find(':checkbox[name^="id"][value="' + id + '"]').prop('checked', marcar);
            } else {
                console.error('[listaRegistros.marcarLinhasID] Formulário não encontrado!');
            } // Fim if ... else
        } // Fim if ... else
    },

    /**
     * Marcar ou desmarcar todas as linhas de uma lista de registros.
     * 
     * @param {Boolean} marcar Define se as linhas / checkboxes deverão ser marcados (TRUE) ou
     * desmarcados (FALSE). Padrão: TRUE
     * @param {Object.jQuery} $form Instância jQuery do formulário a ser considerado
     * para as alterações.
     * 
     * @returns {Void}
     */
    marcarTodasLinhas: function (marcar, $form) {
        $form = $form || listaRegistros.identificarForm(this);
        marcar = typeof marcar === 'undefined' ? true : marcar;
        $form.find(':checkbox[name^="id"]').prop('checked', marcar);
    }
};
