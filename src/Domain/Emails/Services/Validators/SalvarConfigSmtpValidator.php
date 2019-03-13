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

namespace PainelDLX\Domain\Emails\Services\Validators;


use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Exceptions\AutentContaNaoInformadaException;
use PainelDLX\Domain\Emails\Exceptions\AutentSenhaNaoInformadaException;
use PainelDLX\Domain\Emails\Exceptions\NomeSmtpRepetidoException;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;

class SalvarConfigSmtpValidator
{
    /**
     * @var ConfigSmtp
     */
    private $config_smtp;
    /**
     * @var ConfigSmtpRepositoryInterface
     */
    private $confg_smtp_repository;

    /**
     * SalvarConfigSmtpValidator constructor.
     * @param ConfigSmtp $config_smtp
     * @param ConfigSmtpRepositoryInterface $confg_smtp_repository
     */
    public function __construct(ConfigSmtp $config_smtp, ConfigSmtpRepositoryInterface $confg_smtp_repository)
    {
        $this->config_smtp = $config_smtp;
        $this->confg_smtp_repository = $confg_smtp_repository;
    }

    /**
     * Validar as informações passadas antes de salvar essa configuração SMTP
     *
     * @return bool
     * @throws AutentContaNaoInformadaException
     * @throws AutentSenhaNaoInformadaException
     * @throws NomeSmtpRepetidoException
     */
    public function validar(): bool
    {
        $this->verificarNomeRepetido();
        $this->validarAutenticacao();

        return true;
    }

    /**
     * @return bool
     * @throws NomeSmtpRepetidoException
     */
    private function verificarNomeRepetido(): bool
    {
        if ($this->confg_smtp_repository->existsOutroSmtpMesmoNome($this->config_smtp)) {
            throw new NomeSmtpRepetidoException($this->config_smtp->getNome());
        }

        return true;
    }

    /**
     * Se a flag Requer Autenticação estiver ativada, verificar se a conta e a senha para autenticação
     * foram informados.
     * @return bool
     * @throws AutentContaNaoInformadaException
     * @throws AutentSenhaNaoInformadaException
     */
    private function validarAutenticacao(): bool
    {
        if ($this->config_smtp->isRequerAutent()) {
            if (empty($this->config_smtp->getConta())) {
                throw new AutentContaNaoInformadaException();
            }

            if (empty($this->config_smtp->getSenha())) {
                throw new AutentSenhaNaoInformadaException();
            }
        }

        return true;
    }
}