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
use PainelDLX\Domain\Modulos\Repositories\MenuRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\Usuario;

class MenuRepository extends AbstractPainelDLXRepository implements MenuRepositoryInterface
{

    /**
     * Lista de itens para gerar o menu
     * @param Usuario $usuario
     * @return array
     * @throws DBALException
     */
    public function getListaMenu(Usuario $usuario)
    {
        $query = '
            SELECT DISTINCT 
                m.nome AS menu,
                i.nome AS item,
                i.link AS link
            FROM
                dlx_menu m
            INNER JOIN
                dlx_menu_item i ON i.menu_id = m.menu_id
            INNER JOIN
                dlx_menu_item_x_permissao p ON i.menu_item_id = p.menu_item_id
            INNER JOIN
                dlx_permissoes_x_grupos pxg ON pxg.permissao_usuario_id = p.permissao_usuario_id
            INNER JOIN
                dlx_grupos_x_usuarios gxu ON gxu.grupo_usuario_id = pxg.grupo_usuario_id
            WHERE
                m.deletado = 0
                AND i.deletado = 0
                AND gxu.usuario_id = :usuario_id
        ';


        $conn = $this->_em->getConnection();

        $sql = $conn->prepare($query);
        $sql->bindValue(':usuario_id', $usuario->getId());
        $sql->execute();

        $lista_menu = $sql->fetchAll();
        $lista_menu_alterada = [];

        array_map(function ($menu_item) use (&$lista_menu_alterada) {
            $menu = $menu_item['menu'];
            unset($menu_item['menu']);
            $lista_menu_alterada[$menu][] = $menu_item;
        }, $lista_menu);

        return $lista_menu_alterada;
    }
}