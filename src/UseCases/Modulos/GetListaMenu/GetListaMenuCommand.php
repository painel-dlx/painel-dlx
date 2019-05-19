<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 07/02/2019
 * Time: 12:17
 */

namespace PainelDLX\UseCases\Modulos\GetListaMenu;


use PainelDLX\Domain\Usuarios\Entities\Usuario;

class GetListaMenuCommand
{
    /**
     * @var Usuario
     */
    private $usuario;

    /**
     * @return Usuario
     */
    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    /**
     * GetListaMenuCommand constructor.
     * @param Usuario $usuario
     */
    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }
}