<?php
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CurrentUserDataProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private Security $security) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new \RuntimeException('Unauthenticated user.');
        }
        return $user;
    }
}
