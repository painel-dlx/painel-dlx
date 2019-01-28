<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/01/2019
 * Time: 18:18
 */

namespace PainelDLX\Application\Middlewares;

use PainelDLX\Application\Contracts\MiddlewareInterface;

class CriptografarSenhas implements MiddlewareInterface
{
    /**
     * @var string[]
     */
    private $senhas;

    /**
     * CriptografarSenhas constructor.
     * @param string ...$senhas
     */
    public function __construct(string ...$senhas)
    {
        $this->senhas = $senhas;
    }

    public function executar(): void
    {
        foreach ($this->senhas as $senha) {
            if (array_key_exists($senha, $_REQUEST)) {
                $_REQUEST[$senha] = md5(md5($_REQUEST[$senha]));
            }
        }
    }
}