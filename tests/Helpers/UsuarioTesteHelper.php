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

namespace PainelDLX\Testes\Helpers;


use DLX\Infra\EntityManagerX;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\ORMException;
use PainelDLX\Domain\Usuarios\Entities\Usuario;

class UsuarioTesteHelper
{
    /**
     * Cria um novo usuário no bd para teste
     * @param string $nome
     * @param string $email
     * @param string $senha
     * @return Usuario
     * @throws DBALException
     * @throws ORMException
     */
    public static function criarDB(string $nome, string $email, string $senha): Usuario
    {
        $query = 'insert into dlx_usuarios (nome, email, senha) values (:nome, :email, :senha)';

        $con = EntityManagerX::getInstance()->getConnection();
        $sql = $con->prepare($query);
        $sql->bindValue(':nome', $nome, ParameterType::STRING);
        $sql->bindValue(':email', $email, ParameterType::STRING);
        $sql->bindValue(':senha', md5(md5($senha)), ParameterType::STRING);
        $sql->execute();

        $id = $con->lastInsertId();

        /** @var Usuario|null $usuario */
        $usuario = EntityManagerX::getRepository(Usuario::class)->find($id);

        return $usuario;
    }
}