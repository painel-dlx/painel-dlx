<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 05/12/2018
 * Time: 14:06
 */

namespace PainelDLX\Domain\Usuarios\Validators;


use DLX\Contracts\ServiceInterface;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Exceptions\SenhaAtualNaoConfereException;
use PainelDLX\Domain\Usuarios\Exceptions\SenhasInformadasNaoConferemException;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioInvalidoException;
use PainelDLX\Domain\Usuarios\ValueObjects\SenhaUsuario;

class ValidarSenhas
{
    /**
     * Executar o serviço.
     * @param Usuario $usuario
     * @param SenhaUsuario $senha_usuario
     * @return bool
     * @throws UsuarioInvalidoException
     */
    public function validar(
        Usuario $usuario,
        SenhaUsuario $senha_usuario
    ): bool {
        $this->compararSenhaAtual($usuario, $senha_usuario);
        $this->comapararSenhasInformadas($senha_usuario);

        return true;
    }

    /**
     * Comparar a senha atual da SenhaUsuario com a senha da entidade Usuario
     * @param Usuario $usuario
     * @param SenhaUsuario $senha_usuario
     * @return bool
     * @throws UsuarioInvalidoException
     */
    private function compararSenhaAtual(Usuario $usuario, SenhaUsuario $senha_usuario): bool
    {
        if (!$senha_usuario->isResetSenha()) {
            if ($usuario->getId() > 0 && $senha_usuario->getSenhaAtual() !== $usuario->getSenha()) {
                throw UsuarioInvalidoException::senhaAtualNaoConfere();
            }
        }

        return true;
    }

    /**
     * Comparar as senhas informadas
     * @param SenhaUsuario $senha_usuario
     * @return bool
     * @throws UsuarioInvalidoException
     */
    private function comapararSenhasInformadas(SenhaUsuario $senha_usuario): bool
    {
        if ($senha_usuario->getSenhaInformada() !== $senha_usuario->getSenhaConfirm()) {
            throw UsuarioInvalidoException::senhasInformadasNaoConferem();
        }

        return true;
    }
}