<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 31/01/2019
 * Time: 16:25
 */

namespace PainelDLX\Testes\Application\UseCases\Emails\TestarConfigSmtp;

use PainelDLX\UseCases\Emails\TestarConfigSmtp\TestarConfigSmtpCommand;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;

class TestarConfigSmtpCommandTest extends PainelDLXTestCase
{
    /** @var TestarConfigSmtpCommand */
    private $command;

    protected function setUp()
    {
        parent::setUp();
        $this->command = new TestarConfigSmtpCommand(new ConfigSmtp(), 'teste@teste.com');;
    }

    public function test_GetConfigSmtp()
    {
        $this->assertInstanceOf(ConfigSmtp::class, $this->command->getConfigSmtp());
    }

    public function test_GetEmail()
    {
        $this->assertEquals('teste@teste.com', $this->command->getEmail());
    }
}
