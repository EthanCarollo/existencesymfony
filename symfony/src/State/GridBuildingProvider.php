<?php

namespace App\State;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\GridBuilding;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class GridBuildingProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private Security $security) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new \RuntimeException('Unauthenticated user.');
        }


        if ($operation instanceof GetCollection) {
            return $this->entityManager->getRepository(GridBuilding::class)
                ->findBy(['grid' => $user->getGrid()]);
        }

        return null;
    }
}
