<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class Calculator
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function sum(float $a, float $b): float
    {
        $this->logger->alert('Someone has been used calculator');

        return $a + $b;
    }
}