<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/12/2018
 * Time: 08:35
 */

namespace PainelDLX\Application\Middlewares;


use PainelDLX\Application\Contracts\MiddlewareInterface;
use PainelDLX\Application\Middlewares\Exceptions\UsuarioNaoPossuiPermissaoException;

class Autorizacao implements MiddlewareInterface
{
    /**
     * @var string[]
     */
    private $permissoes;

    /**
     * Autorizacao constructor.
     * @param string[] $permissoes
     */
    public function __construct(string ...$permissoes)
    {
        $this->permissoes = $permissoes;
    }

    /**
     * @return bool
     * @throws UsuarioNaoPossuiPermissaoException
     */
    public function executar()
    {
        foreach ($this->permissoes as $permissao) {
            // TODO: se não possuir 1 das permissões, será lançada uma exceção
            if (false) {
                throw new UsuarioNaoPossuiPermissaoException();
            }
        }

        return true;
    }
}