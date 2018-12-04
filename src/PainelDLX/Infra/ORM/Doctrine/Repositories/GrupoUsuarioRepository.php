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

namespace PainelDLX\Infra\ORM\Doctrine\Repositories;


use DLX\Infra\ORM\Doctrine\Repositories\EntityRepository;
use PainelDLX\Domain\CadastroUsuarios\Repositories\GrupoUsuarioRepositoryInterface;

class GrupoUsuarioRepository extends EntityRepository implements GrupoUsuarioRepositoryInterface
{
    /**
     * Selecionar todos os grupos de usuários ativos.
     * @return array
     */
    public function findAtivos(): array
    {
        return $this->findBy([
            'deletado' => false
        ]);
    }

    /**
     * Obter a lista de grupos de usuários por um array de IDs passados.
     * @param int ...$grupo_usuario_id
     * @return array
     */
    public function getListaGruposByIds(int ...$grupo_usuario_id): array
    {
        return $this->findBy([
            'id' => $grupo_usuario_id,
            'deletado' => false
        ]);
    }
}