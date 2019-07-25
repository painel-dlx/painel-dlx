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

namespace PainelDLX\Domain\Common\Entities;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Exception;
use PainelDLX\Domain\Common\Exceptions\ConfiguracaoLogRegistroInvalidaException;
use PainelDLX\Domain\Usuarios\Entities\Usuario;

/**
 * Trait LogRegistroTrait
 * @package PainelDLX\Domain\Common\Entities
 * @method getId()
 * @const TABELA_BD
 */
trait LogRegistroTrait
{
    /** @var Collection */
    private $log;

    /**
     * @return Collection
     */
    public function getLog(): Collection
    {
        if (defined('self::TABELA_BD')) {
            throw ConfiguracaoLogRegistroInvalidaException::nomeTabelaNaoInformado();
        }

        if (is_null($this->log)) {
            $this->log = new ArrayCollection();
        }

        $expr = Criteria::expr();

        $criteria = Criteria::create();
        $criteria->where($expr->eq('tabela', self::TABELA_BD));
        $criteria->andWhere($expr->eq('registro_id', $this->getId()));

        return $this->log->matching($criteria);
    }

    /**
     * @param string $acao
     * @param Usuario|null $usuario
     * @return LogRegistroTrait
     * @throws Exception
     */
    public function addLog(string $acao, ?Usuario $usuario): self
    {
        if (defined('self::TABELA_BD')) {
            throw ConfiguracaoLogRegistroInvalidaException::nomeTabelaNaoInformado();
        }

        if (is_null($this->log)) {
            $this->log = new ArrayCollection();
        }

        $log_registro = new LogRegistro(self::TABELA_BD, $this->getId(), $acao, $usuario);
        $this->log->add($log_registro);

        return $this;
    }
}