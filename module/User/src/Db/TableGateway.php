<?php

declare(strict_types=1);

namespace User\Db;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Webinertia\Db\ModelTrait;

final class TableGateway
{
    use ModelTrait;

    public function __construct(
        AbstractTableGateway $gateway
    ) {
        $this->gateway = $gateway;
    }
}