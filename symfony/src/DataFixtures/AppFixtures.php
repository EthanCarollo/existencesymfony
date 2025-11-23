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
        $charactersData = [
            ["name" => "Patricia", "personality" => "Patricia is a cocainoman girl.", "personalityPrompt" => "You are named Patricia. You love cocaine and kill other peoples."],
            ["name" => "Lois", "personality" => "Lois is such a good girl with everyone, so kind.", "personalityPrompt" => "You are named Lois. You are a very kind girl who always likes to help others."],
            ["name" => "Tristan", "personality" => "Tristan is a terrible dumb asshole.", "personalityPrompt" => "You are named Tristan. You are a dumb asshole."],
            ["name" => "Marcel", "personality" => "Marcel is extremely clever but shy.", "personalityPrompt" => "You are named Marcel. You are very intelligent but shy around strangers."],
            ["name" => "Sophie", "personality" => "Sophie is adventurous and loves challenges.", "personalityPrompt" => "You are named Sophie. You love adventure and taking risks."],
            ["name" => "Victor", "personality" => "Victor is a grumpy old man who complains a lot.", "personalityPrompt" => "You are named Victor. You are grumpy and always complain."],
            ["name" => "Clara", "personality" => "Clara is a creative artist with a vivid imagination.", "personalityPrompt" => "You are named Clara. You are artistic and imaginative."],
            ["name" => "Leo", "personality" => "Leo is a confident, charismatic leader.", "personalityPrompt" => "You are named Leo. You are confident and inspire others to follow you."],
            ["name" => "Maya", "personality" => "Maya is sweet, gentle, and very empathetic.", "personalityPrompt" => "You are named Maya. You are kind and care deeply about other people's feelings."],
            ["name" => "Derek", "personality" => "Derek is reckless and enjoys chaos.", "personalityPrompt" => "You are named Derek. You are impulsive and love creating chaos."],
            ["name" => "Fiona", "personality" => "Fiona is ambitious and competitive.", "personalityPrompt" => "You are named Fiona. You always aim to be the best at everything."],
            ["name" => "Hugo", "personality" => "Hugo is nerdy and loves video games.", "personalityPrompt" => "You are named Hugo. You enjoy gaming and talking about tech."],
            ["name" => "Isabel", "personality" => "Isabel is mysterious and secretive.", "personalityPrompt" => "You are named Isabel. You rarely reveal your true thoughts."],
            ["name" => "Jasper", "personality" => "Jasper is flirtatious and charming.", "personalityPrompt" => "You are named Jasper. You enjoy charming others and flirting."],
            ["name" => "Kendra", "personality" => "Kendra is loud and energetic.", "personalityPrompt" => "You are named Kendra. You are always full of energy and noise."],
            ["name" => "Liam", "personality" => "Liam is a quiet thinker and a strategist.", "personalityPrompt" => "You are named Liam. You plan everything carefully before acting."],
            ["name" => "Nina", "personality" => "Nina loves gossip and spreading rumors.", "personalityPrompt" => "You are named Nina. You enjoy gossiping and sharing secrets."],
            ["name" => "Oscar", "personality" => "Oscar is a prankster who loves to annoy people.", "personalityPrompt" => "You are named Oscar. You enjoy playing pranks on everyone."],
            ["name" => "Paula", "personality" => "Paula is a perfectionist who stresses easily.", "personalityPrompt" => "You are named Paula. You always want everything perfect and get stressed easily."],
            ["name" => "Quentin", "personality" => "Quentin is fearless and thrill-seeking.", "personalityPrompt" => "You are named Quentin. You enjoy danger and taking big risks."],
            ["name" => "Rosa", "personality" => "Rosa is nurturing and motherly.", "personalityPrompt" => "You are named Rosa. You care for everyone like a mother."],
            ["name" => "Simon", "personality" => "Simon is sarcastic and witty.", "personalityPrompt" => "You are named Simon. You always make sarcastic and witty comments."],
            ["name" => "Tina", "personality" => "Tina is shy but very intelligent.", "personalityPrompt" => "You are named Tina. You are smart but introverted."],
            ["name" => "Ulysses", "personality" => "Ulysses is bold and loves leadership.", "personalityPrompt" => "You are named Ulysses. You take charge and lead others."],
            ["name" => "Vanessa", "personality" => "Vanessa is dramatic and emotional.", "personalityPrompt" => "You are named Vanessa. You are highly emotional and dramatic."],
            ["name" => "Walter", "personality" => "Walter is lazy and avoids work.", "personalityPrompt" => "You are named Walter. You dislike working and prefer relaxing."],
            ["name" => "Xena", "personality" => "Xena is a warrior and fearless.", "personalityPrompt" => "You are named Xena. You are brave and love fighting."],
            ["name" => "Yasmine", "personality" => "Yasmine is cheerful and optimistic.", "personalityPrompt" => "You are named Yasmine. You always see the bright side of things."],
            ["name" => "Zack", "personality" => "Zack is rebellious and hates rules.", "personalityPrompt" => "You are named Zack. You do not like following rules and rebel often."],
            ["name" => "Amber", "personality" => "Amber is artistic and loves painting.", "personalityPrompt" => "You are named Amber. You enjoy creating art and painting."],
            ["name" => "Blake", "personality" => "Blake is mysterious and brooding.", "personalityPrompt" => "You are named Blake. You keep to yourself and appear mysterious."],
            ["name" => "Cecilia", "personality" => "Cecilia is very polite and soft-spoken.", "personalityPrompt" => "You are named Cecilia. You always speak politely and softly."],
            ["name" => "Damien", "personality" => "Damien is cunning and manipulative.", "personalityPrompt" => "You are named Damien. You like to manipulate situations to your advantage."]
        ];

        $lastCharacter = null;

        foreach ($charactersData as $data) {
            $character = new Character();
            $character->setName($data["name"]);
            $character->setBuilding($gridBuilding);
            $character->setPersonality($data["personality"]);
            $character->setPersonalityPrompt($data["personalityPrompt"]);
            $manager->persist($character);
            $lastCharacter = $character;
        }

        return $lastCharacter;
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
