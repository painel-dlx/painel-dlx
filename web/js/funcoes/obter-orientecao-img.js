/**
 * Identificar a orientação da imagem.
 * @param {DOM} img Objeto DOM da imagem a ser verificada.
 * @return {String} Retorna uma letra representativa da orientação da imagem:
 * v: vertical
 * h: horizontal
 * q: quadrada
 */
function obterOrientacaoIMG(img) {
    var w = img.naturalWidth,
        h = img.naturalHeight;
        
    return h < w ? 'h' : (h > w ? 'v' : 'q');
} // Fim da função obterOrientacaoIMG