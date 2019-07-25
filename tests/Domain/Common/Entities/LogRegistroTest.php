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

namespace PainelDLX\Tests\Domain\Common\Entities;

use DateTime;
use Exception;
use PainelDLX\Domain\Common\Entities\LogAcao;
use PainelDLX\Domain\Common\Entities\LogRegistro;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PHPUnit\Framework\TestCase;

/**
 * Class LogRegistroTest
 * @package PainelDLX\Tests\Domain\Common\Entities
 * @coversDefaultClass \PainelDLX\Domain\Common\Entities\LogRegistro
 */
class LogRegistroTest extends TestCase
{
    /**
     * @return LogRegistro
     * @throws Exception
     */
    public function test__construct(): LogRegistro
    {
        $tabela = 'dlx_tabela';
        $registro_id = mt_rand(1000, 9999);
        $acao = LogAcao::INSERT;

        /** @var Usuario $usuario */
        $usuario = $this->createMock(Usuario::class);

        $log_registro = new LogRegistro($tabela, $registro_id, $acao, $usuario);

        $this->assertInstanceOf(LogRegistro::class, $log_registro);
        $this->assertEquals($tabela, $log_registro->getTabela());
        $this->assertEquals($registro_id, $log_registro->getRegistroId());
        $this->assertEquals($acao, $log_registro->getAcao());
        $this->assertInstanceOf(DateTime::class, $log_registro->getData());

        return $log_registro;
    }

    /**
     * @covers ::isInsert
     * @throws Exception
     */
    public function test_IsInsert()
    {
        $log_registro_insert = new LogRegistro('dlx_tabela', mt_rand(), LogAcao::INSERT);
        $this->assertTrue($log_registro_insert->isInsert());

        $log_registro_outro = new LogRegistro('dlx_tabela', mt_rand(), LogAcao::DELETE);
        $this->assertFalse($log_registro_outro->isInsert());
    }

    /**
     * @throws Exception
     * @covers ::isUpdate
     */
    public function test_IsUpdate()
    {
        $log_registro_update = new LogRegistro('dlx_tabela', mt_rand(), LogAcao::UPDATE);
        $this->assertTrue($log_registro_update->isUpdate());

        $log_registro_outro = new LogRegistro('dlx_tabela', mt_rand(), LogAcao::DELETE);
        $this->assertFalse($log_registro_outro->isUpdate());
    }

    /**
     * @throws Exception
     * @covers ::isDelete
     */
    public function test_IsDelete()
    {
        $log_registro_delete = new LogRegistro('dlx_tabela', mt_rand(), LogAcao::DELETE);
        $this->assertTrue($log_registro_delete->isDelete());

        $log_registro_outro = new LogRegistro('dlx_tabela', mt_rand(), LogAcao::INSERT);
        $this->assertFalse($log_registro_outro->isDelete());
    }
}
