<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 08:53
 */

namespace PainelDLX\Domain\Usuarios\Repositories;


use DLX\Domain\Repositories\EntityRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Entities\Usuario;

interface ResetSenhaRepositoryInterface extends EntityRepositoryInterface
{
    /**
     * Procura uma solicitação de reset senha ativa para esse usuário.
     * @param Usuario $usuario
     * @return ResetSenha
     */
    public function findResetSenhaAtivoPorUsuario(Usuario $usuario): ?ResetSenha;

    /**
     * Procura uma solicitação de reset de senha pelo hash informado.
     * @param string $hash
     * @return ResetSenha|null
     */
    public function findResetSenhaAtivoPorHash(string $hash): ?ResetSenha;
}