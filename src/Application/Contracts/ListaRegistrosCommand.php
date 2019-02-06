<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 06/02/2019
 * Time: 11:39
 */

namespace PainelDLX\Application\Contracts;


abstract class ListaRegistrosCommand
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
    private $limit;
    /**
     * @var int|null
     */
    private $offset;

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
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * ListaRegistrosCommand constructor.
     * @param array $criteria
     * @param array $order_by
     * @param int|null $limit
     * @param int|null $offset
     */
    public function __construct(array $criteria = [], array $order_by = [], ?int $limit = null, ?int $offset = null)
    {
        $this->criteria = $criteria;
        $this->order_by = $order_by;
        $this->limit = $limit;
        $this->offset = $offset;
    }
}