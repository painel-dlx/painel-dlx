// Configuração ajax: mostrar mensagem em cada requisição AJAX via jQuery
var msgUsuario = {
    msgs: {},

    init: function () {
        this.msgs = this.parse();
    },

    parse: function () {
        var ajax_msg = window.sessionStorage.getItem('ajax-msg') || '{}';
        ajax_msg = JSON.parse(ajax_msg);

        return ajax_msg;
    },

    adicionar: function (mensagem, tipo, id) {
        this.msgs[tipo] = typeof this.msgs[tipo] === 'undefined' ? [] : this.msgs[tipo];
        this.msgs[tipo].push({mensagem: mensagem, id: id});

        this.salvar();
    },

    exibirTodas: function () {
        for (var tipo in this.msgs) {
            for (var i in this.msgs[tipo]) {
                this.exibir(tipo, i);
            }
        }
    },

    exibir: function (tipo, index) {
        var obj_msg = this.msgs[tipo][index];

        this.mostrar(obj_msg.mensagem, tipo, obj_msg.id);

        this.msgs[tipo].splice(index, 1);
        this.salvar();
    },

    salvar: function () {
        window.sessionStorage.setItem('ajax-msg', JSON.stringify(this.msgs));
    },

    mostrar: function (mensagem, tipo, id) {
        id = id || this.uuid();
        var $ajax_msg = $('#' + id);

        if ($ajax_msg.length > 0) {
            $ajax_msg
                .removeClass('-processando')
                .addClass('-' + tipo)
                .find('.status-ajax-texto')
                .html(mensagem);
            return true;
        }

        var $msg = $(document.createElement('div'))
            .addClass('status-ajax-msg')
            .addClass('-' + tipo)
            .attr('id', id)
            .appendTo($('#status-ajax'));

        $(document.createElement('a'))
            .addClass('status-ajax-texto')
            .html(mensagem)
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

        return true;
    },

    fechar: function (id) {
        $('#' + id).fadeOut('fast', function () {
            $(this).remove();
        });
    },

    /**
     * Gerar um ID uníco
     * @returns {string}
     */
    uuid: function () {
        return 'status-ajax-msg-' + Math.random().toString(36).substr(2, 16);
    }
};

msgUsuario.init();
msgUsuario.exibirTodas();

$.ajaxSetup({
    global: true,
    beforeSend: function (xhr, options) {
        if (/.(css|js)$/.test(options.url)) {
            return;
        }

        var $origem = $(document.activeElement);
        var msg = $origem.data('ajax-msg') || options.mensagem || 'Carregando, por favor aguarde.';

        xhr.id = msgUsuario.uuid();
        msgUsuario.mostrar(msg, 'processando', xhr.id);
    },

    error: function (xhr) {
        var msg = 'ERRO ' + xhr.status + ' - ' + xhr.statusText + ': ' + xhr.responseText;
        msgUsuario.mostrar(msg, 'erro', xhr.id);
    },

    complete: function (xhr) {
        window.setTimeout(function () {
            $('#' + xhr.id).find('.status-ajax-fechar').trigger('click');
        }, 7000);
    }
});
