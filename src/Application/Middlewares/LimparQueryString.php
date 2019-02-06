<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 06/02/2019
 * Time: 11:54
 */

namespace PainelDLX\Application\Middlewares;


use PainelDLX\Application\Contracts\MiddlewareInterface;
use PainelDLX\Application\Services\IniciarPainelDLX;

class LimparQueryString implements MiddlewareInterface
{
    /**
     * @var IniciarPainelDLX
     */
    private $painel_dlx;

    /**
     * LimparQueryString constructor.
     * @param IniciarPainelDLX $painel_dlx
     */
    public function __construct(IniciarPainelDLX $painel_dlx)
    {
        $this->painel_dlx = $painel_dlx;
    }

    public function executar(): void
    {
        $request = $this->painel_dlx->getServerRequest();
        $query_string = $request->getQueryParams();
        unset($query_string['ambiente'], $query_string['task']);

        if (array_key_exists('pg-mestra', $query_string)) {
            unset($query_string['pg-mestra']);
        }

        $request_nova = $request->withQueryParams($query_string);
        $this->painel_dlx->setServerRequest($request_nova);
    }
}