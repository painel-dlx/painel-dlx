<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 PHP DLX
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PainelDLX\Infrastructure\ORM\Doctrine\Repositories;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NonUniqueResultException;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;

class UsuarioRepository extends AbstractPainelDLXRepository implements UsuarioRepositoryInterface
{

    /**
     * Verificar se há outro usuário com o mesmo email da entidade informada.
     * @param Usuario $usuario
     * @return bool
     */
    public function hasOutroUsuarioComMesmoEmail(Usuario $usuario): bool
    {
        $lista_usuarios = new ArrayCollection($this->findBy([
            'email' => $usuario->getEmail()
        ]));

        return $lista_usuarios->exists(function ($key, Usuario $usuarioLista) use ($usuario) {
            return $usuarioLista->getId() !== $usuario->getId();
        });
    }

    /**
     * Fazer login
     * @param string $email
     * @param string $senha
     * @return Usuario|null
     * @throws NonUniqueResultException
     */
    public function fazerLogin(string $email, string $senha): ?Usuario
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->andWhere('u.senha = :senha');

        $qb->setParameter('email', $email);
        $qb->setParameter('senha', $senha);

        return $qb->getQuery()->getOneOrNullResult();
    }
}