<?php

namespace App\Service;

use App\Repository\PlatRepository;

class PlatService
{
  private PlatRepository $platRepository;

  public function __construct(PlatRepository $platRepository)
  {
    $this->platRepository = $platRepository;
  }
}