<?php

namespace App;

use Doctrine\DBAL\Logging\SQLLogger as SqlLoggerInterface;

class SqlLogger implements SqlLoggerInterface
{
    /** @var Logger */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->logger->log('INFO.sql', $sql. ' with params '.implode(', ', $params ?? []));
    }

    public function stopQuery()
    {
        // TODO: Implement stopQuery() method.
    }
}