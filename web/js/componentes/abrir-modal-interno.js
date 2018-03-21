/* global console, adicionarEvento */

function __abrirModalInterno(seletor) {
    var $modal = $(document.createElement('div')).addClass('caixa-modal').html('<p class="teclaesc">Pressione a tecla ESC para fechar.</p>'),
        $cont_modal = $(seletor);

    if ($cont_modal.length !== 1) {
        console.error('[__abrirModalInterno] Objeto não encontrado.');
        return;   
    } // Fim if

    $cont_modal.clone(true, true)
        // Adicionar a classe .contmodal para identificar esse conteúdo e possibilitar
        // a manipulação do visual dele
        .addClass('contmodal')
        
        // Forçar o conteúdo do modal a aparecer, prevenindo display:none;
        .css('display', 'initial').appendTo($modal);

    $modal.appendTo($('body'));
} // Fim __abrirModalInterno

adicionarEvento($('[data-acao="abrir-modal-interno"]'), 'click.__abrirModalInterno', function () {
    __abrirModalInterno.apply(this, [$(this).data('modal-seletor')]);
});

// Configurar a tecla ESC para fechar o modal
adicionarEvento($('body'), 'keyup.__abrirModalInterno', function (evt) {
    var kc = evt.keycode || evt.which;

    if (kc === 27) {
        $('.caixa-modal').fadeOut('fast', function() {
            $(this).remove();
        });
    } // Fim if
});