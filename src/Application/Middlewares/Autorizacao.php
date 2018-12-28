<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/12/2018
 * Time: 08:35
 */

namespace PainelDLX\Application\Middlewares;


use PainelDLX\Application\Contracts\MiddlewareInterface;

class Autorizacao implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $permissao;

    /**
     * Autorizacao constructor.
     * @param string $permissao
     */
    public function __construct(string $permissao)
    {
        $this->permissao = $permissao;
    }

    /**
     * @return bool
     */
    public function executar()
    {
        return true;
    }
}