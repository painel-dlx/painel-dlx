<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 05/12/2018
 * Time: 14:06
 */

namespace PainelDLX\Domain\Usuarios\Services;


use DLX\Contracts\ServiceInterface;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Exceptions\SenhaAtualNaoConfereException;
use PainelDLX\Domain\Usuarios\Exceptions\SenhasInformadasNaoConferemException;
use PainelDLX\Domain\Usuarios\ValueObjects\SenhaUsuario;

class VerificaSenhasIguais implements ServiceInterface
{
    /** @var Usuario */
    private $usuario;
    /** @var SenhaUsuario */
    private $senha_usuario;

    public function __construct(Usuario $usuario, SenhaUsuario $senha_usuario)
    {
        $this->usuario = $usuario;
        $this->senha_usuario = $senha_usuario;
    }

    /**
     * Executar o serviço.
     * @return mixed
     * @throws SenhaAtualNaoConfereException
     * @throws SenhasInformadasNaoConferemException
     */
    public function executar()
    {
        $this->compararSenhaAtual();
        $this->comapararSenhasInformadas();

        return true;
    }

    /**
     * Comparar a senha atual da SenhaUsuario com a senha da entidade Usuario
     * @return bool
     * @throws SenhaAtualNaoConfereException
     */
    private function compararSenhaAtual(): bool
    {
        if ($this->usuario->getUsuarioId() > 0 && $this->senha_usuario->getSenhaAtual() !== $this->usuario->getSenha()) {
            throw new SenhaAtualNaoConfereException();
        }

        return true;
    }

    /**
     * Comparar as senhas informadas
     * @return bool
     * @throws SenhasInformadasNaoConferemException
     */
    private function comapararSenhasInformadas(): bool
    {
        if ($this->senha_usuario->getSenhaInformada() !== $this->senha_usuario->getSenhaConfirm()) {
            throw new SenhasInformadasNaoConferemException();
        }

        return true;
    }
}