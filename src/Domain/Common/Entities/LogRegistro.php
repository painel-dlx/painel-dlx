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


use DateTime;
use DLX\Domain\Entities\Entity;
use Exception;
use PainelDLX\Domain\Usuarios\Entities\Usuario;

/**
 * Class LogRegistro
 * @package PainelDLX\Domain\Common\Entities
 * @covers LogRegistroTest
 */
class LogRegistro extends Entity
{
    /** @var int|null */
    private $id;
    /** @var string */
    private $tabela;
    /** @var mixed */
    private $registro_id;
    /** @var DateTime */
    private $data;
    /** @var string */
    private $acao;
    /** @var Usuario|null */
    private $usuario;

    /**
     * LogRegistro constructor.
     * @param string $tabela
     * @param mixed $registro_id
     * @param string $acao
     * @param Usuario|null $usuario
     * @throws Exception
     */
    public function __construct(string $tabela, $registro_id, string $acao = 'I', ?Usuario $usuario = null)
    {
        $this->tabela = $tabela;
        $this->registro_id = $registro_id;
        $this->data = new DateTime();
        $this->acao = $acao;
        $this->usuario = $usuario;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTabela(): string
    {
        return $this->tabela;
    }

    /**
     * @return mixed
     */
    public function getRegistroId()
    {
        return $this->registro_id;
    }

    /**
     * @return DateTime
     */
    public function getData(): DateTime
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getAcao(): string
    {
        return $this->acao;
    }

    /**
     * @return Usuario|null
     */
    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    /**
     * Verifica se o log é referente ao insert do registro
     * @return bool
     */
    public function isInsert(): bool
    {
        return $this->getAcao() === LogAcao::INSERT;
    }

    /**
     * Verifica se o log é referente ao update do registro
     * @return bool
     */
    public function isUpdate(): bool
    {
        return $this->getAcao() === LogAcao::UPDATE;
    }

    /**
     * Verifica se o log é referente ao delete do registro
     * @return bool
     */
    public function isDelete(): bool
    {
        return $this->getAcao() === LogAcao::DELETE;
    }
}