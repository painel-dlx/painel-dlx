/**
 * Created by dlepera on 04/02/16.
 */

/* global console */
// jshint unused:false

/**
 * Calcular o dígito verificador do CPF ou CNPJ
 *
 * @param {string} v Número de documento a ser calculado (apenas números)
 * @param {Array} m Vetor contendo os valores multiplicadores
 * @returns {number}
 * @constructor
 */
function calcDV(v, m) {
    var qtm = m.length, soma = 0, mod;

    for (var i = 0; i < qtm; i++) {
        soma += m[i] * parseInt(v[i]);
    } // Fim for( i )

    mod = soma % 11;

    // Encontrar DV
    return mod < 2 ? 0 : 11 - mod;
} // Fim function calcDV(v, m)


/**
 * Validação de CPF
 *  - Dígitos verificadores e tamanho
 *
 * @param {string} v Pode receber o CPF puro (apenas números) ou com máscara
 * @returns {Boolean}
 */
function validaCPF(v) {
    var dv_1, dv_2;
    var mlt1 = [10, 9, 8, 7, 6, 5, 4, 3, 2];
    var mlt2 = [11, 10, 9, 8, 7, 6, 5, 4, 3, 2];
    var cpf = v.replace(/[^0-9]+/g, '');

    dv_1 = calcDV(cpf, mlt1);
    dv_2 = calcDV(cpf, mlt2);

    return dv_1 === parseInt(cpf[9]) && dv_2 === parseInt(cpf[10]);
} // Fim function validaCPF(cpf_sujo)


/**
 * Validar CNPJ
 *
 * @param {string} v Valor de CNPJ a ser verificado
 * @returns {boolean}
 * @constructor
 */
function validaCNPJ(v) {
    var dv_1, dv_2;
    var mlt1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    var mlt2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    var cnpj = v.replace(/[^0-9]+/g, '');

    /*
     * Aguardar o CNPJ completo para fazer a verificação
     */
    if (cnpj.length < 14) {
        return true;
    } // Fim if

    dv_1 = calcDV(cnpj, mlt1);
    dv_2 = calcDV(cnpj, mlt2);

    return dv_1 === parseInt(cnpj[12]) && dv_2 === parseInt(cnpj[13]);
} // Fim function validaCNPJ(v)


/**
 * Validar EAN 8, EAN 13 e EAN 14 seguindo normas GTIN
 *
 * @param {string} ean Valor EAN a ser validado
 * @returns {boolean}
 * @constructor
 */
function validaEAN(ean) {
    var gtin = [8, 13, 14];

    if (gtin.indexOf(ean.length) < 0) {
        console.warn('O número informado não segue os padrões: GTIN-' + gtin.join(', GTIN-'));
    } // Fim if

    var s = 0, dv, dvo = parseInt(ean.substr(-1)), qt = ean.length, q = ean.length - 1, m = 3;

    for (var x = 0; x < q; x++) {
        if (qt > 13) {
            s += x % 2 === 0 ? parseInt(ean[x]) * m : parseInt(ean[x]);
        } else {
            s += x % 2 === 0 ? parseInt(ean[x]) : parseInt(ean[x]) * m;
        } // Fim if
    } // Fim for(x)

    var clc = (1000 + s) % 10;
    dv = clc === 0 ? 0 : 10 - clc;

    return dv === dvo;
} // Fim function validaEAN()


/**
 * Validar tamanho e extensão dos arquivos para upload
 *
 * @param a Objeto com as informações necessárias para a validação
 * @returns {boolean}
 * @constructor
 */
function validaUpload(a) {
    var arqs = a.arq;
    var exts = a.exts;
    var tmax = parseInt(a.max) * 1024 * 1024;
    var qtd = arqs.length;
    var arq, nome, ext;

    for (var i = 0; i < qtd; i++) {
        arq = arqs[i];
        nome = arq.name.toLowerCase();
        ext = nome.substr(nome.indexOf('.', nome.length - 5) + 1);

        if (arq.size > tmax || exts.indexOf(ext) < 0) {
            return false;
        }
    } // Fim for(i)

    return true;
} // Fim validaUpload(a)


/**
 * Alterar a mensagem de validação de acordo com o status de validação e a condição
 *
 * @param {bool} condicao Expressão booleana para definir a alteração da mensagem
 * @param {object} campo DOM do campo a ser validado
 * @param {string} mensagem Mensagem a ser exibida para o caso do campo não ser validado corretamente
 * @returns {*}
 */
function formValidacao(condicao, campo, mensagem) {
    return condicao ? campo.setCustomValidity(mensagem)
        : !campo.validity.valid && campo.validationMessage === mensagem ? campo.setCustomValidity('')
        : null;
} // Fim function Validacao(consicao, campo, mensagem)