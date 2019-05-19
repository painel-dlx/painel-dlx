// Configuração ajax: mostrar mensagem em cada requisição AJAX via jQuery
(function ($) {
    $.fn.getSelector = function () {
        var $this = $(this);
        var jqSelector = '';

        if (typeof $this.attr('id') !== 'undefined') {
            jqSelector += '#' + $this.attr('id');
        } else if (typeof $this.attr('class') !== 'undefined') {
            jqSelector += '.' + $this.attr('class').replace(/\s+/g, '.');
        }

        return jqSelector;
    };

    $.getSelector = function (dom) {
        return $(dom).getSelector();
    };

    $.ajaxSetup({
        global: true,
        beforeSend: function (xhr, options) {
            if (!/\.(js|css)$/.test(options.url)) {
                options.context = document.activeElement;
                var $origem = $(options.context);
                var msg = $origem.data('ajax-msg') || 'Carregando, por favor aguarde.';

                var $msg = $(document.createElement('div'))
                    .addClass('status-ajax-msg')
                    .addClass('-processando')
                    .attr('data-ajax-origem', $origem.getSelector())
                    .appendTo($('#status-ajax'));

                $(document.createElement('a'))
                    .addClass('status-ajax-texto')
                    .html(msg)
                    .appendTo($msg);

                $(document.createElement('a'))
                    .addClass('status-ajax-fechar')
                    .text('x')
                    .attr({
                        href: 'javascript:',
                        title: 'Fechar'
                    })
                    .on('click', function () {
                        $(this).parents('.status-ajax-msg').fadeOut('fast', function () {
                            $(this).remove();
                        });
                    })
                    .appendTo($msg);
            }
        }
    });

    $(document).ajaxSuccess(function (event, xhr, options) {
        var $status_ajax_msg = $('#status-ajax [data-ajax-origem="' + $.getSelector(options.context) + '"]');
        var json, retorno, mensagem;

        if (typeof xhr.responseJSON === 'undefined') {
            try {
                json = JSON.parse(xhr.responseText);
                retorno = json.retorno;
                mensagem = json.mensagem;
            } catch (e) {
                // retorno = 'sucesso';
                // mensagem = xhr.responseText;

                $status_ajax_msg.fadeOut('fast', function () {
                    $(this).remove();
                });

                return false;
            }
        } else {
            json = xhr.responseJSON;
            retorno = json.retorno;
            mensagem = json.mensagem;
        }

        $status_ajax_msg
            .removeClass('-processando')
            .addClass('-' + retorno)
            .find('.status-ajax-texto')
            .html(mensagem);
    });

    $(document).ajaxError(function (event, xhr, options) {
        var $status_ajax_msg = $('#status-ajax [data-ajax-origem="' + $.getSelector(options.context) + '"]');

        $status_ajax_msg
            .removeClass('-processando')
            .addClass('-erro')
            .find('.status-ajax-texto')
            .html('ERRO ' + xhr.status + ' - ' + xhr.statusText + ': ' + xhr.responseText);
    });

    $(document).ajaxComplete(function (event, xhr, options) {
        var $status_ajax_msg = $('#status-ajax [data-ajax-origem="' + $.getSelector(options.context) + '"]');

        window.setTimeout(function () {
            $status_ajax_msg.fadeOut('fast', function () {
                $(this).remove();
            });
        }, 10000);
    });
})(jQuery);