<?php

declare(strict_types=1);

namespace App\Controller\Ai;

use App\Entity\Character;
use App\Entity\Chat;
use App\Repository\CharacterRepository;
use App\Services\AI\AIChat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'chat', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        set_time_limit(240);
        $aiChat = AIChat::getInstance();
        $charRepository = $entityManager->getRepository(Character::class);
        $chatRepository = $entityManager->getRepository(Chat::class);

        $parameters = json_decode($request->getContent(), true);
        $initialCharacterId = $parameters['character1'];
        $recipientCharacterId = $parameters['character2'];

        $initialCharacter = $charRepository->find($initialCharacterId);
        $recipientCharacter = $charRepository->find($recipientCharacterId);

        $previousChat = $chatRepository->findBy([
            "receiver" => $initialCharacter,
            "sended" => $recipientCharacter
        ]);

        $reverseChat = $chatRepository->findBy([
            "receiver" => $recipientCharacter,
            "sended" => $initialCharacter
        ]);

        $allChats = array_merge($previousChat, $reverseChat);
        $sender = $initialCharacter;
        $receiver = $recipientCharacter;
        $message = "";
        if(count($allChats) > 0) {
            usort($allChats, fn($a, $b) => $a->getCreatedAt() <=> $b->getCreatedAt());
            $sender = end($allChats)->getSendedId() == $initialCharacter->getId() ? $recipientCharacter : $initialCharacter;
            $receiver = end($allChats)->getSendedId() == $initialCharacter->getId() ? $initialCharacter : $recipientCharacter;
            $message = end($allChats)->getMessage();
            array_pop($allChats);
        }
        $message = $aiChat->chat(
            $sender,
            $receiver,
            $message,
            "darkidol-llama-3.1-8b-instruct-1.2-uncensored-iq-imatrix-request",
            $allChats
        );


        $chat = new Chat();
        $chat->setReceiver($receiver);
        $chat->setSended($sender);
        $chat->setMessage($message);
        $chat->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($chat);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $chat->getId(),
            'senderId' => $chat->getSended()->getId(),
            'receiverId' => $chat->getReceiver()->getId(),
            'message' => $chat->getMessage(),
            'createdAt' => $chat->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
