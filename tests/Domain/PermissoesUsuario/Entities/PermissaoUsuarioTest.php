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

namespace PainelDLX\Testes\Domain\PermissoesUsuarios\Entities;


use Doctrine\Common\Collections\Collection;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Exceptions\PermissaoUsuarioJaPossuiGrupoException;
use PHPUnit\Framework\TestCase;

/**
 * Class PermissaoUsuarioTest
 * @package PainelDLX\Testes\Domain\PermissoesUsuarios\Entities
 * @coversDefaultClass \PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario
 */
class PermissaoUsuarioTest extends TestCase
{
    /** @var string */
    const ALIAS = 'CADASTRAR_NOVO_USUARIO';
    /** @var string */
    const DESCRICAO = 'Cadastrar um novo usuário';

    /**
     * @return PermissaoUsuario
     * @covers ::__construct
     */
    public function test__construct(): PermissaoUsuario
    {
        $alias = 'PERMISSAO_TESTE';
        $descricao = 'PERMISSAO_TESTE';

        $permissao_usuario = new PermissaoUsuario($alias, $descricao);

        $this->assertEquals($alias, $permissao_usuario->getAlias());
        $this->assertEquals($descricao, $permissao_usuario->getDescricao());
        $this->assertInstanceOf(Collection::class, $permissao_usuario->getGrupos());
        $this->assertInstanceOf(Collection::class, $permissao_usuario->getItensMenu());

        return $permissao_usuario;
    }

    public function test_create_PermissaoUsuario(): void
    {
        $permissao_usuario = PermissaoUsuario::create(self::ALIAS, self::DESCRICAO);

        $this->assertInstanceOf(PermissaoUsuario::class, $permissao_usuario);
        $this->assertEquals(self::ALIAS, $permissao_usuario->getAlias());
        $this->assertEquals(self::DESCRICAO, $permissao_usuario->getDescricao());
    }

    /**
     * @throws PermissaoUsuarioJaPossuiGrupoException
     */
    public function test_adicionar_grupos_iguais()
    {
        $permissao_usuario = PermissaoUsuario::create(self::ALIAS, self::DESCRICAO);
        $grupo_usuario = GrupoUsuario::create('Admin');

        $this->expectException(PermissaoUsuarioJaPossuiGrupoException::class);
        $permissao_usuario->addGrupo($grupo_usuario);
        $permissao_usuario->addGrupo($grupo_usuario);
    }

    /**
     * O alias seguirá um padrão de ser sempre em letras maiúsculas e sem espaços
     */
    public function test_setAlias_formatacao()
    {
        $permissao_usuario = PermissaoUsuario::create('Teste de Alias', self::DESCRICAO);

        $this->assertEquals('TESTE_DE_ALIAS', $permissao_usuario->getAlias());
    }
}