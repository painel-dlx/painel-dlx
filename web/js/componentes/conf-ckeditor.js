/* global CKEDITOR, hostInfos */

/**
 * Setar as configurações do CKEDITOR, e iniciar o plugin.
 * Para funcionar corretamente é necessário que o plugin ckeditor seja carregado antes do arquivo painel-dlx.js.
 * Obs: Se usar o evento onload não funciona, pois o CKEDITOR carrega antes.
 */
if (typeof CKEDITOR !== 'undefined') {
    // Desabilitar a ativação do editor em qualquer elemento setado como 'contenteditable'
    CKEDITOR.disableAutoInline = true;

    // Adicionar classes extras para tipos de formatação pré-definidos. Isso servirá para facilitar
    // a aplicação do tema.
    CKEDITOR.stylesSet.add('default', [
        { name: 'Título 1', element: 'h1', attributes: { 'class': 'titulo-h1' } },
        { name: 'Título 2', element: 'h2', attributes: { 'class': 'titulo-h2' } }
    ]);

    CKEDITOR.config.coreStyles_bold = { element: 'b' };

    // Carregar plugins extras
    // TODO: Depois que a função PHP citada abaixo para upload de arquivos for feita, deve-se ativar
    // os seguintes plugins extras: uploadfile,uploadimage,uploadwidget,filebrowser,filetools
    CKEDITOR.config.extraPlugins = 'lineutils,notification,notificationaggregator,widget,widgetselection,popup';

    // Configurar a URL com a função PHP usada para fazer o Upload.
    // TODO: Recriar a função, uma vez que o ap-global foi descontinuado.
    // CKEDITOR.config.uploadUrl = '../ap-global/ckeditor/upload-arquivos/copia-e-cola';

    // Iniciar o ckeditor nos elementos que possuem o atributo data-ckeditor="true"
    $('[data-ckeditor="true"]').each(function () {
        var $this = $(this), id = this.id;
        CKEDITOR.replace(id, $this.data());
        
        $this.parents('form').on('submit.__ckeditor', function () {
            $this.val(
                $('#cke_' + id).find('iframe').contents().find('[contenteditable]').html()
            );
        });
    });
} // Fim if