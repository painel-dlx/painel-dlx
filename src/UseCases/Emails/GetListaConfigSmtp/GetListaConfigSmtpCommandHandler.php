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

namespace PainelDLX\UseCases\Emails\GetListaConfigSmtp;

use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;

class GetListaConfigSmtpCommandHandler
{
    /**
     * @var ConfigSmtpRepositoryInterface
     */
    private $config_smtp_repository;

    /**
     * GetListaConfigSmtpCommandHandler constructor.
     * @param ConfigSmtpRepositoryInterface $config_smtp_repository
     */
    public function __construct(ConfigSmtpRepositoryInterface $config_smtp_repository)
    {
        $this->config_smtp_repository = $config_smtp_repository;
    }

    /**
     * @param GetListaConfigSmtpCommand $command
     * @return array
     */
    public function handle(GetListaConfigSmtpCommand $command): array
    {
        $criteria = $command->getCriteria();
        $criteria['and'] = ['deletado' => false];

        return $this->config_smtp_repository->findByLike(
            $criteria,
            $command->getOrderBy(),
            $command->getLimit(),
            $command->getOffset()
        );
    }
}