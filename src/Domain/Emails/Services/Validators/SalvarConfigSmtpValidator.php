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
use PainelDLX\Domain\Emails\Exceptions\ConfigSmtpInvalidoException;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;

/**
 * Class SalvarConfigSmtpValidator
 * @package PainelDLX\Domain\Emails\Services\Validators
 * @covers SalvarConfigSmtpValidatorTest
 */
class SalvarConfigSmtpValidator
{
    /**
     * @var ConfigSmtpRepositoryInterface
     */
    private $confg_smtp_repository;

    /**
     * SalvarConfigSmtpValidator constructor.
     * @param ConfigSmtpRepositoryInterface $confg_smtp_repository
     */
    public function __construct(ConfigSmtpRepositoryInterface $confg_smtp_repository)
    {
        $this->confg_smtp_repository = $confg_smtp_repository;
    }

    /**
     * Validar as informações passadas antes de salvar essa configuração SMTP
     *
     * @param ConfigSmtp $config_smtp
     * @return bool
     * @throws ConfigSmtpInvalidoException
     */
    public function validar(ConfigSmtp $config_smtp): bool
    {
        $this->verificarNomeRepetido($config_smtp);
        $this->validarAutenticacao($config_smtp);

        return true;
    }

    /**
     * @param ConfigSmtp $config_smtp
     * @return bool
     * @throws ConfigSmtpInvalidoException
     */
    private function verificarNomeRepetido(ConfigSmtp $config_smtp): bool
    {
        if ($this->confg_smtp_repository->existsOutroSmtpMesmoNome($config_smtp)) {
            throw ConfigSmtpInvalidoException::nomeJaEstaSendoUtilizado($config_smtp->getNome());
        }

        return true;
    }

    /**
     * Se a flag Requer Autenticação estiver ativada, verificar se a conta e a senha para autenticação
     * foram informados.
     * @param ConfigSmtp $config_smtp
     * @return bool
     * @throws ConfigSmtpInvalidoException
     */
    private function validarAutenticacao(ConfigSmtp $config_smtp): bool
    {
        if ($config_smtp->isRequerAutent()) {
            if (empty($config_smtp->getConta())) {
                throw ConfigSmtpInvalidoException::contaAutenticacaoNaoInformado();
            }

            if (empty($config_smtp->getSenha())) {
                throw ConfigSmtpInvalidoException::senhaAutenticacaoNaoInformada();
            }
        }

        return true;
    }
}