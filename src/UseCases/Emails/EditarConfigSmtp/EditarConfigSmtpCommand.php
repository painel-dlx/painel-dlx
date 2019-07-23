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

namespace PainelDLX\UseCases\Emails\EditarConfigSmtp;


use PainelDLX\Domain\Emails\Entities\ConfigSmtp;

class EditarConfigSmtpCommand
{
    /**
     * @var ConfigSmtp
     */
    private $config_smtp;
    /**
     * @var string
     */
    private $nome;
    /**
     * @var string
     */
    private $servidor;
    /**
     * @var int
     */
    private $porta;
    /**
     * @var string|null
     */
    private $cripto;
    /**
     * @var bool
     */
    private $requer_autent;
    /**
     * @var string|null
     */
    private $conta;
    /**
     * @var string|null
     */
    private $senha;
    /**
     * @var string|null
     */
    private $de_nome;
    /**
     * @var string|null
     */
    private $responder_para;
    /**
     * @var bool
     */
    private $corpo_html;

    /**
     * EditarConfigSmtpCommand constructor.
     * @param ConfigSmtp $config_smtp
     * @param string $nome
     * @param string $servidor
     * @param int $porta
     * @param string|null $cripto
     * @param bool $requer_autent
     * @param string|null $conta
     * @param string|null $senha
     * @param string|null $de_nome
     * @param string|null $responder_para
     * @param bool $corpo_html
     */
    public function __construct(
        ConfigSmtp $config_smtp,
        string $nome,
        string $servidor,
        int $porta,
        ?string $cripto = null,
        bool $requer_autent = false,
        ?string $conta = null,
        ?string $senha = null,
        ?string $de_nome = null,
        ?string $responder_para = null,
        bool $corpo_html = false
    ) {
        $this->config_smtp = $config_smtp;
        $this->nome = $nome;
        $this->servidor = $servidor;
        $this->porta = $porta;
        $this->cripto = $cripto;
        $this->requer_autent = $requer_autent;
        $this->conta = $conta;
        $this->senha = $senha;
        $this->de_nome = $de_nome;
        $this->responder_para = $responder_para;
        $this->corpo_html = $corpo_html;
    }

    /**
     * @return ConfigSmtp
     */
    public function getConfigSmtp(): ConfigSmtp
    {
        return $this->config_smtp;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @return string
     */
    public function getServidor(): string
    {
        return $this->servidor;
    }

    /**
     * @return int
     */
    public function getPorta(): int
    {
        return $this->porta;
    }

    /**
     * @return string|null
     */
    public function getCripto(): ?string
    {
        return $this->cripto;
    }

    /**
     * @return bool
     */
    public function isRequerAutent(): bool
    {
        return $this->requer_autent;
    }

    /**
     * @return string|null
     */
    public function getConta(): ?string
    {
        return $this->conta;
    }

    /**
     * @return string|null
     */
    public function getSenha(): ?string
    {
        return $this->senha;
    }

    /**
     * @return string|null
     */
    public function getDeNome(): ?string
    {
        return $this->de_nome;
    }

    /**
     * @return string|null
     */
    public function getResponderPara(): ?string
    {
        return $this->responder_para;
    }

    /**
     * @return bool
     */
    public function isCorpoHtml(): bool
    {
        return $this->corpo_html;
    }
}