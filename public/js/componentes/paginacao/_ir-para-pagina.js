/**
 * Ir para uma determinada página de regitros
 * @param {int} pagina
 */
function irParaPagina(pagina) {
    var pathname = window.location.pathname;
    var query_string = window.location.search;

    // Não deixar que a página seja zerada ou fique negativa
    pagina = pagina < 1 ? 1 : pagina;

    if (!query_string) {
        query_string = '?pg=' + pagina;
    } else if (query_string.indexOf('pg=') < 0) {
        query_string += '&pg=' + pagina;
    } else {
        query_string = query_string.replace(/pg=\d+/, 'pg=' + pagina);
    }

    window.location.assign(pathname + query_string);
}