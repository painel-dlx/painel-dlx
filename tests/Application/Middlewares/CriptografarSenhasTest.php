<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 29/01/2019
 * Time: 14:39
 */

namespace PainelDLX\Testes\Application\Middlewares;

use PainelDLX\Application\Middlewares\CriptografarSenhas;
use PainelDLX\Application\Services\PainelDLX;
use PainelDLX\Tests\TestCase\PainelDLXTestCase;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class CriptografarSenhasTest
 * @package PainelDLX\Testes\Application\Middlewares
 * @coversDefaultClass \PainelDLX\Application\Middlewares\CriptografarSenhas
 */
class CriptografarSenhasTest extends PainelDLXTestCase
{
    /**
     * @covers ::process
     */
    public function test_Process()
    {
        $this->markTestSkipped('Implementar o teste da Middleware CriptografarSenhas');
    }
}
