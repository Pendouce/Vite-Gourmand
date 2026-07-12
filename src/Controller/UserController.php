<?php

namespace App\Controller;

use App\Service\UserService;
use App\Repository\UserRepository;


class UserController extends Controller
{
  private UserService $userService;

  public function __construct() {
    $userRepository = new UserRepository();
    $this->userService = new UserService($userRepository);
  }
}