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

namespace PainelDLX\Infrastructure\ORM\Doctrine\Repositories;


use DLX\Domain\Entities\Entity;
use DLX\Infrastructure\ORM\Doctrine\Repositories\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use PainelDLX\Domain\Common\Entities\LogAcao;
use PainelDLX\Domain\Common\Entities\LogRegistro;
use PainelDLX\Domain\Common\Entities\LogRegistroTrait;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use Throwable;

/**
 * Class AbstractPainelDLXRepository
 * @package PainelDLX\Infrastructure\ORM\Doctrine\Repositories
 * @todo: adicionar suporte para salvar o usuário logado no log do registro
 * @todo: Por enquanto o log será salvo manualmente, mas vou tentar usar o cascade ou por eventos
 */
abstract class AbstractPainelDLXRepository extends EntityRepository
{
    /**
     * Verifica se a entidade informada possui LOG
     * @param Entity $entity
     * @return bool
     */
    private function hasLog(Entity $entity): bool
    {
        $lista_traits = class_uses($entity, true);
        return in_array(LogRegistroTrait::class, $lista_traits);
    }

    /**
     * Por enquanto o log será gravado manualmente
     * @param string $tabela
     * @param $registro_id
     * @param string $acao
     * @param Usuario|null $usuario
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    private function addLog(string $tabela, $registro_id, string $acao, ?Usuario $usuario): void
    {
        $log_registro = new LogRegistro($tabela, $registro_id, $acao, $usuario);
        parent::create($log_registro);
    }

    /**
     * @param Entity $entity
     * @throws Throwable
     */
    public function create(Entity $entity): void
    {
        $this->_em->transactional(function () use ($entity) {
            parent::create($entity);

            if ($this->hasLog($entity)) {
                $this->addLog($entity::TABELA_BD, $entity->getId(), LogAcao::INSERT, null);
            }
        });
    }

    /**
     * @param Entity $entity
     * @throws Throwable
     */
    public function update(Entity $entity): void
    {
        $this->_em->transactional(function () use ($entity) {
            parent::update($entity);

            if ($this->hasLog($entity)) {
                $this->addLog($entity::TABELA_BD, $entity->getId(), LogAcao::UPDATE, null);
            }
        });
    }

    /**
     * @param Entity $entity
     * @throws Throwable
     */
    public function delete(Entity $entity): void
    {
        $this->_em->transactional(function () use ($entity) {
            if ($this->hasLog($entity)) {
                $this->addLog($entity::TABELA_BD, $entity->getId(), LogAcao::DELETE, null);
            }

            parent::delete($entity);
        });
    }
}