<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 15/02/2019
 * Time: 09:03
 */

namespace PainelDLX\Testes\Application\UseCases\Home\GetListaWidgets;

use DLX\Infra\EntityManagerX;
use PainelDLX\Application\UseCases\Home\GetListaWigets\GetListaWidgetsCommand;
use PainelDLX\Application\UseCases\Home\GetListaWigets\GetListaWidgetsCommandHandler;
use PainelDLX\Domain\Home\Entities\Widget;
use PainelDLX\Domain\Home\Repositories\WidgetRepositoryInterface;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;

class GetListaWidgetsHandlerTest extends PainelDLXTestCase
{
    /** @var GetListaWidgetsCommandHandler */
    private $handler;

    protected function setUp()
    {
        parent::setUp();

        /** @var WidgetRepositoryInterface $widget_repository */
        $widget_repository = EntityManagerX::getRepository(Widget::class);
        $this->handler = new GetListaWidgetsCommandHandler($widget_repository);
    }

    public function test_Handle_deve_retornar_array()
    {
        $command = new GetListaWidgetsCommand();
        $lista_widgets = $this->handler->handle($command);

        $this->assertTrue(is_array($lista_widgets));
    }
}
