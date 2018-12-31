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

namespace PainelDLX\Testes\Domain\Usuarios\Entities;


use PainelDLX\Domain\CadastroUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\CadastroUsuarios\Entities\PermissaoUsuario;
use PainelDLX\Domain\CadastroUsuarios\Exceptions\PermissaoUsuarioJaPossuiGrupoException;
use PHPUnit\Framework\TestCase;

class PermissaoUsuarioTest extends TestCase
{
    /** @var string */
    const ALIAS = 'CADASTRAR_NOVO_USUARIO';
    /** @var string */
    const DESCRICAO = 'Cadastrar um novo usuário';

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