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

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\ParameterType;
use PainelDLX\Domain\Modulos\Repositories\MenuRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\Usuario;

/**
 * Class MenuRepository
 * @package PainelDLX\Infrastructure\ORM\Doctrine\Repositories
 * @covers MenuRepositoryTest
 */
class MenuRepository extends AbstractPainelDLXRepository implements MenuRepositoryInterface
{
    /**
     * Lista de itens para gerar o menu
     * @param Usuario $usuario
     * @return array
     */
    public function getListaMenu(Usuario $usuario): array
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select(['partial m.{id, nome}, partial i.{id, nome, link}'])
            ->innerJoin('m.itens', 'i')
            ->innerJoin('i.permissoes', 'p')
            ->innerJoin('p.grupos', 'g')
            ->innerJoin('g.usuarios', 'u')
            ->where('m.deletado = 0')
            ->andWhere('i.deletado = 0')
            ->andWhere('u.id = :usuario_id')
            ->setParameter(':usuario_id', $usuario->getId(), ParameterType::INTEGER);

        $query = $qb->getQuery();

        return $query->getResult();
    }
}