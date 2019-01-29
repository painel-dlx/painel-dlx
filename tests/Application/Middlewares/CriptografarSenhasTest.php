<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 29/01/2019
 * Time: 14:39
 */

namespace PainelDLX\Testes\Application\Middlewares;

use PainelDLX\Application\Middlewares\CriptografarSenhas;
use PainelDLX\Testes\PainelDLXTests;
use Zend\Diactoros\ServerRequestFactory;

$server_request = ServerRequestFactory::fromGlobals();

class CriptografarSenhasTest extends PainelDLXTests
{

    public function test_Executar()
    {
        global $server_request;

        $senha = 'teste';
        $server_request = $server_request->withQueryParams([
            'senha' => $senha
        ]);

        $cripto = new CriptografarSenhas('senha');
        $cripto->executar();

        $this->assertEquals(md5(md5($senha)), $server_request->getQueryParams()['senha']);
    }
}
