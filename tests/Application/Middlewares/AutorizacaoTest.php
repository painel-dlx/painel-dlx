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

namespace PainelDLX\Testes\Application\Middlewares;

use DLX\Core\Exceptions\ArquivoConfiguracaoNaoEncontradoException;
use DLX\Core\Exceptions\ArquivoConfiguracaoNaoInformadoException;
use Doctrine\ORM\ORMException;
use PainelDLX\Application\Middlewares\Autorizacao;
use PainelDLX\Application\Middlewares\Exceptions\UsuarioNaoPossuiPermissaoException;
use PainelDLX\Application\Services\Exceptions\AmbienteNaoInformadoException;
use PainelDLX\Domain\GruposUsuarios\Exceptions\GrupoJaPossuiPermissaoException;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioJaPossuiGrupoException;
use PainelDLX\Testes\Domain\Usuarios\Entities\UsuarioTest;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;
use SechianeX\Contracts\SessionInterface;
use SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException;
use SechianeX\Exceptions\SessionAdapterNaoEncontradoException;
use SechianeX\Factories\SessionFactory;

class AutorizacaoTest extends PainelDLXTestCase
{
    /** @var SessionInterface */
    private $session;

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     * @throws ArquivoConfiguracaoNaoInformadoException
     * @throws ORMException
     * @throws AmbienteNaoInformadoException
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     */
    protected function setUp()
    {
        parent::setUp();
        $this->session = SessionFactory::createPHPSession();
    }

    /**
     * @throws GrupoJaPossuiPermissaoException
     * @throws UsuarioJaPossuiGrupoException
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     * @throws UsuarioNaoPossuiPermissaoException
     */
    public function test_Executar_retorna_true_quando_usuario_possui_permissao()
    {
        $usuario = (new UsuarioTest())->test_hasPermissao_deve_retornar_bool();
        $this->session->set('usuario-logado', $usuario);

        $autorizacao = new Autorizacao('TESTE');
        $this->assertTrue($autorizacao->executar());
    }

    /**
     * @throws GrupoJaPossuiPermissaoException
     * @throws UsuarioJaPossuiGrupoException
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     * @throws UsuarioNaoPossuiPermissaoException
     */
    public function test_Executar_lanca_uma_UsuarioNaoPossuiPermissaoException_quando_usuario_NAO_possui_permissao()
    {
        $usuario = (new UsuarioTest())->test_hasPermissao_deve_retornar_bool();
        $this->session->set('usuario-logado', $usuario);

        $this->expectException(UsuarioNaoPossuiPermissaoException::class);
        $autorizacao = new Autorizacao('OUTRO_TESTE');
        $autorizacao->executar();
    }
}
