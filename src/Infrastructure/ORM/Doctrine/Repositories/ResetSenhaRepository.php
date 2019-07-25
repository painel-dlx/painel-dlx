<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 14/01/2019
 * Time: 08:56
 */

namespace PainelDLX\Infrastructure\ORM\Doctrine\Repositories;

use Exception;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\ResetSenhaRepositoryInterface;

class ResetSenhaRepository extends AbstractPainelDLXRepository implements ResetSenhaRepositoryInterface
{

    /**
     * Procura uma solicitação de reset senha ativa para esse usuário.
     * @param Usuario $usuario
     * @return ResetSenha
     * @throws Exception
     */
    public function findResetSenhaAtivoPorUsuario(Usuario $usuario): ?ResetSenha
    {
        /** @var ResetSenha $reset_senha */
        $reset_senha = $this->findOneBy([
            'usuario' => $usuario,
            'utilizado' => false
        ]);

        return is_null($reset_senha) || $reset_senha->isExpirado() ? null : $reset_senha;
    }

    /**
     * Procura uma solicitação de reset de senha pelo hash informado.
     * @param string $hash
     * @return ResetSenha|null
     * @throws Exception
     */
    public function findResetSenhaAtivoPorHash(string $hash): ?ResetSenha
    {
        /** @var ResetSenha $reset_senha */
        $reset_senha = $this->findOneBy([
            'hash' => $hash,
            'utilizado' => false
        ]);

        return is_null($reset_senha) || $reset_senha->isExpirado() ? null : $reset_senha;
    }
}