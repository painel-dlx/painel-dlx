<?php
/**
 * Created by PhpStorm.
 * User: dlepera88
 * Date: 01/12/2018
 * Time: 00:23
 */

namespace PainelDLX\Domain\Usuarios\Entities;


use DLX\Domain\Entities\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioJaPossuiGrupoException;

class Usuario extends Entity
{
    /** @var int */
    private $usuario_id;
    /** @var string */
    private $nome;
    /** @var string */
    private $email;
    /** @var string */
    private $senha;
    /** @var bool */
    private $deletado = false;
    /** @var Collection */
    private $grupos;

    /**
     * @return int
     */
    public function getUsuarioId(): ?int
    {
        return $this->usuario_id;
    }

    /**
     * @param int $usuario_id
     * @return Usuario
     */
    public function setUsuarioId(int $usuario_id): Usuario
    {
        $this->usuario_id = $usuario_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return Usuario
     */
    public function setNome(string $nome): Usuario
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Usuario
     */
    public function setEmail(string $email): Usuario
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenha(): string
    {
        return $this->senha;
    }

    /**
     * @param string $senha
     * @return Usuario
     */
    public function setSenha(string $senha): Usuario
    {
        $this->senha = $senha;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDeletado(): bool
    {
        return $this->deletado;
    }

    /**
     * @param bool $deletado
     * @return Usuario
     */
    public function setDeletado(bool $deletado): Usuario
    {
        $this->deletado = $deletado;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getGrupos(): Collection
    {
        return $this->grupos;
    }

    /**
     * @param GrupoUsuario $grupoUsuario
     * @return Usuario
     * @throws UsuarioJaPossuiGrupoException
     */
    public function addGrupo(GrupoUsuario $grupoUsuario): Usuario
    {
        if ($this->hasGrupoUsuario($grupoUsuario)) {
            throw new UsuarioJaPossuiGrupoException($grupoUsuario->getNome());
        }

        $this->grupos->add($grupoUsuario);
        return $this;
    }

    /**
     * Usuario constructor.
     * @param string $nome
     * @param string $email
     * @param GrupoUsuario $grupo_usuario
     */
    public function __construct(string $nome, string $email)
    {
        $this->grupos = new ArrayCollection();
        $this->setNome($nome);
        $this->setEmail($email);
    }

    /**
     * @param string $nome
     * @param string $email
     * @param GrupoUsuario ...$grupo_usuario
     * @return Usuario
     * @throws UsuarioJaPossuiGrupoException
     */
    public static function create(string $nome, string $email, GrupoUsuario ...$grupo_usuario): Usuario
    {
        $usuario = (new self($nome, $email))
            ->setNome($nome)
            ->setEmail($email);

        /** @var GrupoUsuario $grupo */
        foreach ($grupo_usuario as $grupo) {
            $usuario->addGrupo($grupo);
        }

        return $usuario;
    }

    /**
     * Verificar se esse usuário já possui um determinado grupo de usuário
     * @param GrupoUsuario $grupo_usuario
     * @return bool
     */
    public function hasGrupoUsuario(GrupoUsuario $grupo_usuario): bool
    {
        return $this->grupos->contains($grupo_usuario);
    }

    /**
     * @param string $alias
     * @return bool
     */
    public function hasPermissao(string $alias): bool
    {
        return $this->getGrupos()->exists(function ($key, GrupoUsuario $grupo_usuario) use ($alias) {
            return $grupo_usuario->getPermissoes()->exists(function ($key, PermissaoUsuario $permissao_usuario) use ($alias) {
                return $permissao_usuario->getAlias() === $alias;
            });
        });
    }

    public function __toString()
    {
        return $this->getNome() ?? '';
    }
}