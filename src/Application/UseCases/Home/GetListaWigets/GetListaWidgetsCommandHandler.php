<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 15/02/2019
 * Time: 09:00
 */

namespace PainelDLX\Application\UseCases\Home\GetListaWigets;


use PainelDLX\Domain\Home\Repositories\WidgetRepositoryInterface;

class GetListaWidgetsCommandHandler
{
    /**
     * @var WidgetRepositoryInterface
     */
    private $widget_repository;

    /**
     * GetListaWidgetsCommandHandler constructor.
     * @param WidgetRepositoryInterface $widget_repository
     */
    public function __construct(WidgetRepositoryInterface $widget_repository)
    {
        $this->widget_repository = $widget_repository;
    }

    /**
     * @param GetListaWidgetsCommand $command
     * @return array
     */
    public function handle(GetListaWidgetsCommand $command): array
    {
        return $this->widget_repository->findBy(['ativo' => true]);
    }
}