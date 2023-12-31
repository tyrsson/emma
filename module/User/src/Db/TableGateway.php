<?php

declare(strict_types=1);

namespace User\Db;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\AbstractTableGateway;

final class TableGateway
{
    public function __construct(
        private AbstractTableGateway $gateway
    ) {
    }
    public function getTable(): string
    {
        return $this->gateway->getTable();
    }
    public function getAdapter(): AdapterInterface
    {
        return $this->gateway->getAdapter();
    }
}