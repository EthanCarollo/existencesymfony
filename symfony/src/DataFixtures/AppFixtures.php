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
        $user = $manager->getRepository(User::class)->findOneBy(['email' => 'tomato@gmail.com']);
        $grid = $manager->getRepository(Grid::class)->findOneBy(['user' => $user]);
        $this->createGridBuilding($manager, $building, $grid);
        $user = $manager->getRepository(User::class)->findOneBy(['email' => 'test123@gmail.com']);
        $grid = $manager->getRepository(Grid::class)->findOneBy(['user' => $user]);
        $gridBuilding = $this->createGridBuilding($manager, $building, $grid);
        $character = $this->createCharacter($manager, $gridBuilding);
        $manager->flush();
    }

    private function createCharacter(ObjectManager $manager, GridBuilding $gridBuilding): Character
    {
        $character = new Character();
        $character->setName("Patricia");
        $character->setBuilding($gridBuilding);
        $character->setPersonality("Patricia is a cocainoman girl.");
        $character->setPersonalityPrompt("You are named Patrica. You love cocaine and kill other peoples.");
        $manager->persist($character);
        $character = new Character();
        $character->setName("Lois");
        $character->setBuilding($gridBuilding);
        $character->setPersonality("Lois is such a good girl with everyone, so kind.");
        $character->setPersonalityPrompt("You are named Lois. You are a very kind girl who always like to help each other.");
        $manager->persist($character);
        $character = new Character();
        $character->setName("Tristan");
        $character->setBuilding($gridBuilding);
        $character->setPersonality("Tristan is a terrible dumb asshole.");
        $character->setPersonalityPrompt("You are named Tristan. You are a dumb asshole");
        $manager->persist($character);
        return $character;
    }

    private function createGridBuilding(ObjectManager $manager, Buildings $building, Grid $grid): GridBuilding
    {
        $gridBuilding = new GridBuilding();
        $gridBuilding->setBuilding($building);
        $gridBuilding->setXPos(-2);
        $gridBuilding->setYPos(-4);
        $grid->addGridBuilding($gridBuilding);

        $manager->persist($gridBuilding);
        $manager->flush();

        return $gridBuilding;
    }

    private function createBuildings(ObjectManager $manager): Buildings
    {
        $building = new Buildings();
        $building->setName("abandonned_house");
        $building->setImage("/images/abandonned_house.png");
        $building->setModel("/models/abandonned_house.glb");
        $building->setHeight(2);
        $building->setLength(3);
        $building->setWidth(2);

        $manager->persist($building);

        $building = new Buildings();
        $building->setName("apartment");
        $building->setImage("/images/apartment.png");
        $building->setModel("/models/apartment.glb");
        $building->setHeight(3);
        $building->setLength(2);
        $building->setWidth(2);

        $manager->persist($building);

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
            ['name' => 'Peanut', 'email' => 'test123@gmail.com'],
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
