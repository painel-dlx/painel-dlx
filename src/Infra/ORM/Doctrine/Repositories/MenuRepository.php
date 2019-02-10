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

namespace PainelDLX\Infra\ORM\Doctrine\Repositories;


use DLX\Infra\ORM\Doctrine\Repositories\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use PainelDLX\Domain\Modulos\Entities\Menu;
use PainelDLX\Domain\Modulos\Entities\MenuItem;
use PainelDLX\Domain\Modulos\Entities\MenuItemPermissao;
use PainelDLX\Domain\Modulos\Repositories\MenuRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\Usuario;

class MenuRepository extends EntityRepository implements MenuRepositoryInterface
{

    /**
     * Lista de itens para gerar o menu
     * @return array
     */
    public function getListaMenu(Usuario $usuario)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('partial a.{nome}, partial i.{nome, link}')
            ->from(Menu::class, 'm')
            ->innerJoin(MenuItem::class, 'i', Join::WITH, 'i.menu_id = m.menu_id and i.deletado = 0')
            ->innerJoin(MenuItemPermissao::class, 'p', Join::WITH, 'p.menu_item = i.menu_item_id')
            ->innerJoin('dlx_permissao_x_grupos', 'pxg', Join::WITH, 'p.permissao = pxg.permissao_usuario_id')
            ->innerJoin('dlx_grupos_x_usuarios', 'gxu', Join::WITH, 'gxu.permissao_usuario_id = pxg.permissao_usuario_id')
            ->where('m.deletado = 0')
            ->andWhere('i.deletado = 0')
            ->andWhere('gxu.usuario_id = :usuario_id')
            ->setParameter(':usuario_id', $usuario->getUsuarioId());

        return  $qb->getQuery()->getResult();
    }
}