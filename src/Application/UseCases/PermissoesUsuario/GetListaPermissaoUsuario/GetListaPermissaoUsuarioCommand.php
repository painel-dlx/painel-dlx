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

namespace PainelDLX\Application\UseCases\PermissoesUsuario\GetListaPermissaoUsuario;


class GetListaPermissaoUsuarioCommand
{
    /**
     * @var array
     */
    private $criteria;
    /**
     * @var array
     */
    private $order_by;
    /**
     * @var int|null
     */
    private $offset;
    /**
     * @var int|null
     */
    private $limit;

    /**
     * @return array
     */
    public function getCriteria(): array
    {
        return $this->criteria;
    }

    /**
     * @return array
     */
    public function getOrderBy(): array
    {
        return $this->order_by;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * GetListaConfigSmtpCommand constructor.
     * @param array $criteria
     * @param array $order_by
     * @param int|null $offset
     * @param int|null $qtde
     */
    public function __construct(array $criteria, array $order_by, ?int $offset = null, ?int $qtde = null)
    {
        $this->criteria = $criteria;
        $this->order_by = $order_by;
        $this->offset = $offset;
        $this->limit = $qtde;
    }
}