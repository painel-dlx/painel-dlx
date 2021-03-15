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

namespace PainelDLX\Tests\Infrastructure\ORM\Doctrine\Repositories;

use DLX\Infrastructure\EntityManagerX;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use PainelDLX\Domain\Modulos\Entities\Menu;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Infrastructure\ORM\Doctrine\Repositories\MenuRepository;
use PainelDLX\Tests\TestCase\PainelDLXTestCase;

/**
 * Class MenuRepositoryTest
 * @package PainelDLX\Tests\Infrastructure\ORM\Doctrine\Repositories
 * @coversDefaultClass \PainelDLX\Infrastructure\ORM\Doctrine\Repositories\MenuRepository
 */
class MenuRepositoryTest extends PainelDLXTestCase
{
    /**
     * @throws DBALException
     * @throws ORMException
     * @covers ::getListaMenu
     */
    public function test_GetListaMenu_deve_retornar_array_com_informacoes_do_menu()
    {
        $query = 'select 
                    u.usuario_id 
                from 
                    dlx.Usuario u
                inner join
                    dlx.GrupoUsuario_x_Usuario gu on gu.usuario_id = u.usuario_id
                order by 
                     rand() 
                limit 1';
        $sql = EntityManagerX::getInstance()->getConnection()->executeQuery($query);
        $usuario_id = $sql->fetchColumn();

        $usuario = $this->createMock(Usuario::class);
        $usuario->method('getId')->willReturn($usuario_id);

        /** @var Usuario $usuario */

        /** @var MenuRepository $repository */
        $repository = EntityManagerX::getRepository(Menu::class);
        $lista_menu = $repository->getListaMenu($usuario);

        $this->assertIsArray($lista_menu);
        $this->assertTrue(count($lista_menu) > 0);

        foreach ($lista_menu as $menu) {
            $this->assertInstanceOf(Menu::class, $menu);
        }
    }
}
