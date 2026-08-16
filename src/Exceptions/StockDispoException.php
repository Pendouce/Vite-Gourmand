<?php

namespace App\Exceptions;

use Exception;

    class StockDispoException extends Exception
{
    public function __construct(int $stock, string $type)
    {
        parent::__construct($stock." disponible pour ".$type);
    }
}