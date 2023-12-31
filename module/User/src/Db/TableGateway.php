<?php

declare(strict_types=1);

namespace User\Db;

use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Hydrator\ReflectionHydrator;
use Webinertia\Db\ModelTrait;

final class TableGateway
{
    use ModelTrait;

    private ReflectionHydrator $hydrator;

    public function __construct(
        AbstractTableGateway $gateway
    ) {
        $this->gateway = $gateway;
        $this->hydrator = new ReflectionHydrator();
    }

    public function save(EntityInterface $entity): EntityInterface|int
    {
        $set = $this->hydrator->extract($entity);
        if ($set === []) {
            throw new \InvalidArgumentException('Repository can not save empty entity.');
        }
        try {
            if (! isset($set['id']) ) {
                // insert
                $this->gateway->insert($set);
                $set['id'] = $this->gateway->getLastInsertValue();
            } else {
                if (isset($set['password']) || $set['password'] === null) {
                    unset($set['password']);
                }
                 $this->gateway->update($set, ['id' => $set['id']]);
            }
        } catch (\Throwable $th) {
            // todo: add logging, throw exception
            throw $th;
        }
        return $this->hydrator->hydrate($set, $entity);
    }

    public function fetchRow(string $column, mixed $value, ?array $columns = null): EntityInterface
    {
        $where = new Where();
        $where->equalTo($column, $value);
        $select = $this->gateway->getSql()->select();
        $select->where($where);
        if ($columns !== null) {
            return $this->gateway->selectWith($select)->current();
        }
        return $this->gateway->select($where)->current();
    }
}