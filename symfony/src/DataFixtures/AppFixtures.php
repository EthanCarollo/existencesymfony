<?php

namespace App\DataFixtures;

use App\Entity\Buildings;
use App\Entity\Character;
use App\Entity\Grid;
use App\Entity\GridBuilding;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->createUsers($manager);
        $building = $this->createBuildings($manager);
        $gridBuilding = $this->createGridBuilding($manager, $building);
        $character = $this->createCharacter($manager, $gridBuilding);
        $manager->flush();
    }

    private function createCharacter(ObjectManager $manager, GridBuilding $gridBuilding): Character {
        $character = new Character();
        $character->setName("Tristan");
        $character->setImage("tristan.jpg");
        $character->setBuilding($gridBuilding);
        $character->setPersonality("You are a dumb asshole");
        $manager->persist($character);
        return $character;
    }

    private function createGridBuilding(ObjectManager $manager, Buildings $building): GridBuilding
    {
        $user = $manager->getRepository(User::class)->findOneBy(['email' => 'test1234@gmail.com']);
        if (!$user) {
            throw new \Exception('Utilisateur test1234@gmail.com non trouvé');
        }
        $grid = $manager->getRepository(Grid::class)->findOneBy(['user' => $user]);
        if (!$grid) {
            throw new \Exception('Grille pour test1234@gmail.com non trouvée');
        }

        $gridBuilding = new GridBuilding();
        $gridBuilding->setGrid($grid);
        $gridBuilding->setBuilding($building);
        $gridBuilding->setXPos(2);
        $gridBuilding->setYPos(4);

        $manager->persist($gridBuilding);
        $manager->flush();

        return $gridBuilding;
    }

    private function createBuildings(ObjectManager $manager): Buildings
    {
        $building = new Buildings();
        $building->setName("warehouse");
        $building->setImage("/images/warehouse.png");
        $building->setModel("/models/warehouse.glb");
        $building->setHeight(2);
        $building->setLength(3);
        $building->setWidth(2);

        $manager->persist($building);

        return $building;
        $manager->flush();
    }

    private function createUsers(ObjectManager $manager): void
    {
        $usersData = [
            ['name' => 'Cucumber', 'email' => 'test1234@gmail.com'],
            ['name' => 'Lettuce', 'email' => 'lettuce@gmail.com'],
            ['name' => 'Tomato', 'email' => 'tomato@gmail.com'],
            ['name' => 'Carrot', 'email' => 'carrot@gmail.com'],
        ];

        foreach ($usersData as $data) {
            $user = new User();
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'test'));
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);

            $grid = new Grid();
            $grid->setSize(20);
            $grid->setUser($user);

            $manager->persist($grid);
        }
        $manager->flush();
    }
}
