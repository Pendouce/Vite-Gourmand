<?php

namespace App\Exceptions;

use Exception;

    class StockDispoException extends Exception
{
    public function __construct(int $stock, string $type)
    {
        parent::__construct("Stock disponible pour ".$type." : ".$stock);
    }
}