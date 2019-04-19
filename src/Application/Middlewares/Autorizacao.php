<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 28/12/2018
 * Time: 08:35
 */

namespace PainelDLX\Application\Middlewares;


use DLX\Infra\EntityManagerX;
use PainelDLX\Application\Contracts\MiddlewareInterface;
use PainelDLX\Application\Middlewares\Exceptions\UsuarioNaoPossuiPermissaoException;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use SechianeX\Contracts\SessionInterface;
use SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException;
use SechianeX\Exceptions\SessionAdapterNaoEncontradoException;
use SechianeX\Factories\SessionFactory;

class Autorizacao implements MiddlewareInterface
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var string[]
     */
    private $permissoes;

    /**
     * Autorizacao constructor.
     * @param string ...$permissoes
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     */
    public function __construct(string ...$permissoes)
    {
        // TODO: Tentar desacoplar a sessão
        $this->session = SessionFactory::createPHPSession();
        $this->permissoes = $permissoes;
    }

    /**
     * @return bool
     * @throws UsuarioNaoPossuiPermissaoException
     */
    public function executar(): bool
    {
        /** @var Usuario $usuario */
        $usuario = $this->session->get('usuario-logado');

        foreach ($this->permissoes as $permissao) {
            if (!$usuario->hasPermissao($permissao)) {
                throw new UsuarioNaoPossuiPermissaoException();
            }
        }

        return true;
    }
}