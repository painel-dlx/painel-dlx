/* global adicionarEvento, obterOrientacaoIMG */

// Previsualização de imagens para uploads -------------------------------------------------------------------------- //
adicionarEvento($('.previs-imagem [type="file"]'), 'change.__previsImagem', function () {
    var $this = $(this),
        $previs = $this.parents('.previs-imagem'),
        $imagem = $previs.find('img'),
        tmpimg = URL.createObjectURL(this.files[0]),
        img_orient;

    if ($imagem.length > 0) {
        $imagem.css('display', 'none').attr('src', tmpimg).fadeIn('slow');
    } else {
        $imagem = $(document.createElement('img')).attr('src', tmpimg).css('display', 'none').appendTo($this.parents('.previs-imagem')).fadeIn('slow');
    } // Fim if ... else

    // Adiconalmente, eu troco ou adiciono uma classe 'parâmetro' no .previs-imagem de acordo com a orientação
    // da imagem para que seja possível manipular a exibição, caso necessário.
    $imagem.on('load.__previsImage', function () {
        if (typeof obterOrientacaoIMG === 'function') { 
            img_orient = obterOrientacaoIMG(this);
            $previs.removeClass('-v -h -q');
            $previs.addClass('-' + img_orient);
        } // Fim if
    });
});