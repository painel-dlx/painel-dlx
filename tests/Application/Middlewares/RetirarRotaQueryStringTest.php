<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 06/02/2019
 * Time: 11:58
 */

namespace PainelDLX\Testes\Application\Middlewares;

use PainelDLX\Application\Middlewares\LimparQueryString;
use PainelDLX\Application\Services\IniciarPainelDLX;
use PainelDLX\Testes\PainelDLXTests;
use Zend\Diactoros\ServerRequestFactory;

class RetirarRotaQueryStringTest extends PainelDLXTests
{

    public function test_Executar()
    {
        $server_request = ServerRequestFactory::fromGlobals();
        $server_request = $server_request->withQueryParams([
            'ambiente' => 'dev',
            'task' => '/',
            'teste' => 'teste'
        ]);

        $painel_dlx = new IniciarPainelDLX($server_request);
        (new LimparQueryString($painel_dlx))->executar();

        $dlx_server_request = $painel_dlx->getServerRequest();
        $this->assertArrayNotHasKey('ambiente', $dlx_server_request->getQueryParams());
        $this->assertArrayNotHasKey('task', $dlx_server_request->getQueryParams());
        $this->assertArrayNotHasKey('pg-mestra', $dlx_server_request->getQueryParams());
    }
}
