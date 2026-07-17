<?php

namespace App\Test\TestUserServiceTest;

use App\Exceptions\EmailMdpException;
use App\Repository\UserRepository;
use App\Service\MailService;
use App\Service\UserService;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ConnexionTest extends TestCase
{
  public function testConnexionMauvaisMail(): void
  {
    // Arrange
    $email = 'tes@email.com';
    $mdp = 'Test123';

    $mockUserRepository = $this->createMock(UserRepository::class);
    $mockUserRepository->method('trouveUtilisateurByEmail')->willReturn(false);

    $mockMailService = $this->createMock(MailService::class);

    $userService = new UserService($mockUserRepository, $mockMailService);
    
    // Except
    $this->expectException(EmailMdpException::class);

    // Act
    $userService->connexion($email, $mdp);
  }

  public function testConnexionMauvaisMdp(): void
  {
    // Arrange

    $email = 'tes@email.com';
    $mdp = 'Test123';

    $utilisateur = User::creerEtHydrate([
      'user_id' => 1,
      'email' => $email,
      'mot_de_passe' => 'azerty',
    ]);

    $mockUserRepository = $this->createMock(UserRepository::class);
    $mockUserRepository->method('trouveUtilisateurByEmail')->willReturn($utilisateur);

    $mockMailService = $this->createMock(MailService::class);

    $userService = new UserService($mockUserRepository, $mockMailService);
    
    // Assert
    $this->expectException(EmailMdpException::class);
    // Act
    $userService->connexion($email, $mdp);
  }

  public function testConnexionReussie(): void
  {
    // Arrange

    $email = 'tes@email.com';
    $mdp = 'Test123';
    $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);

    $utilisateur = User::creerEtHydrate([
      'user_id' => 1,
      'email' => $email,
      'mot_de_passe' => $mdpHash,
    ]);

    $mockUserRepository = $this->createMock(UserRepository::class);
    $mockUserRepository->method('trouveUtilisateurByEmail')->willReturn($utilisateur);

    $mockMailService = $this->createMock(MailService::class);

    $userService = new UserService($mockUserRepository, $mockMailService);
    
    // Act
    $result = $userService->connexion($email, $mdp);

    // Assert
    $this->assertInstanceOf(User::class, $result);
  }

}