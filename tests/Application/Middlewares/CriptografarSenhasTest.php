<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 29/01/2019
 * Time: 14:39
 */

namespace PainelDLX\Testes\Application\Middlewares;

use PainelDLX\Application\Middlewares\CriptografarSenhas;
use PainelDLX\Application\Services\IniciarPainelDLX;
use PainelDLX\Testes\PainelDLXTests;
use Zend\Diactoros\ServerRequestFactory;

$painel_dlx = new IniciarPainelDLX(ServerRequestFactory::fromGlobals());

class CriptografarSenhasTest extends PainelDLXTests
{

    public function test_Executar()
    {
        global $painel_dlx;

        $senha = 'teste';
        $server_request = $painel_dlx->getServerRequest();
        $painel_dlx->setServerRequest($server_request->withQueryParams(['senha' => $senha]));

        $cripto = new CriptografarSenhas('senha');
        $cripto->executar();

        $this->assertEquals(md5(md5($senha)), $painel_dlx->getServerRequest()->getQueryParams()['senha']);
    }
}
