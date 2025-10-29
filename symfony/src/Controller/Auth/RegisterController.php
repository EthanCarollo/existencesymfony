<?php

namespace App\Controller\Auth;

use App\Entity\Grid;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class RegisterController extends AbstractController
{
    public function __invoke(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, JWTTokenManagerInterface $jwtManager)
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER']);

        $em->persist($user);
        $em->flush();

        $grid = new Grid();
        $grid->setSize(20);
        $grid->setUser($user);

        $em->persist($grid);
        $em->flush();

        $token = $jwtManager->create($user);

        return new JsonResponse(['token' => $token], 201);
    }
}
