<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/01/2019
 * Time: 17:56
 */

namespace PainelDLX\UseCases\Usuarios\GetUsuarioPeloId;


class GetUsuarioPeloIdCommand
{
    /**
     * @var int
     */
    private $usuario_id;

    /**
     * @return int
     */
    public function getUsuarioId(): int
    {
        return $this->usuario_id;
    }

    /**
     * GetUsuarioPeloIdCommand constructor.
     * @param int $usuario_id
     */
    public function __construct(int $usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }
}