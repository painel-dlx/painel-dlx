/* global adicionarEvento */

function __abrirModalExterno(url, pg_mestra) {
    var $modal = $(document.createElement('div')).addClass('caixa-modal').html('<p class="teclaesc">Pressione a tecla ESC para fechar.</p>');
    pg_mestra = pg_mestra || 'conteudo';

    $.ajax({
        url: url + (url.indexOf('?') > -1 ? '&' : '?') +  'dlx-mestra=' + pg_mestra,
        success: function (html) {
            $modal.html($modal.html() + html).find('.dlx-conteudo').addClass('contmodal');
            $modal.appendTo($('body'));
        }
    });
    
    $modal.appendTo($('body'));
} // Fim __abrirModalExterno

adicionarEvento($('[data-acao="abrir-modal-externo"]'), 'click.__abrirModalExterno', function () {
    var params = $(this).data();
    __abrirModalExterno.apply(this, [params['modal-url'], params['modal-mestra']]);
});

// Configurar a tecla ESC para fechar o modal
adicionarEvento($('body'), 'keyup.__abrirModalExterno', function (evt) {
    var kc = evt.keycode || evt.which;

    if (kc === 27) {
        $('.caixa-modal').fadeOut('fast', function() {
            $(this).remove();
        });
    } // Fim if
});