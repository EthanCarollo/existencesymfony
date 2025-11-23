<?php
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CurrentCharacterProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private Security $security) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new \RuntimeException('Unauthenticated user.');
        }

        $gridBuildings = $user->getGrid()->getGridBuildings();
        $characters = [];
        foreach ($gridBuildings as $building) {
            foreach ($building->getCharacters() as $character) {
                $characters[] = $character;
            }
        }
        return $characters;
    }
}
