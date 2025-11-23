<?php


namespace App\Services\AI;

use App\Entity\Character;
use App\Entity\Chat;
use Symfony\AI\Platform\Bridge\Ollama\Contract\OllamaContract;
use Symfony\AI\Platform\Bridge\Ollama\OllamaClient;
use Symfony\AI\Platform\Bridge\Ollama\OllamaResultConverter;
use Symfony\AI\Platform\Bridge\Ollama\PlatformFactory;
use Symfony\AI\Platform\Exception\ExceptionInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Platform;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OllamaAIChat
{
    private static ?self $instance = null;
    private \Symfony\AI\Platform\Platform $platform;
    private string $defaultHost = 'https://ollama.sample.ethan-folio.fr';

    private function __construct(string $host = null, ?HttpClientInterface $httpClient = null)
    {
        $host = $host ?? $this->defaultHost;

        //dd($host);

        $httpClient = $httpClient ?? HttpClient::create();

        $this->platform = new Platform(
            [new OllamaClient($httpClient, $host)],
            [new OllamaResultConverter()],
            new CompletionsModelCatalog(),
            $contract ?? OllamaContract::create(),
            null,
        );
    }

    public static function getInstance(string $host = null, ?HttpClientInterface $httpClient = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($host, $httpClient);
        }

        return self::$instance;
    }

    /**
     * @throws ExceptionInterface
     */
    public function chat(Character $author = null,
                         Character $receiver = null,
                         string    $message,
                         string    $modelName = 'llama2-uncensored',
                         array     $previousChats = []): string
    {
        $historyString = implode("\n", array_map(function ($obj) {
            $name = $obj->getSended()->getName();
            $msg = trim($obj->getMessage());
            return $msg !== "" ? $name . " : " . $msg : null;
        }, $previousChats));
        $historyString = implode("\n", array_filter(explode("\n", $historyString)));

        $conversationPrompt = $message === ""
            ? "Start the conversation with " . $receiver->getName() . ". Reply naturally, in your own words, as this character."
            : "Here is the history of messages between you and " . $receiver->getName() . ":\n" .
            $historyString . "\n" .
            "Now respond to the latest message from " . $receiver->getName() . ":\n" .
            $message . "\n\n" .
            "Important: Respond naturally, do NOT prepend your name or any labels; only write what the character would actually say.";

        $messages = new MessageBag();
        $messages->add(Message::ofUser(
            "You are now embodying this personality:\n" .
            $author->getPersonalityPrompt() . "\n\n" .
            $conversationPrompt
        ));


        $result = $this->platform->invoke($modelName, $messages);
        return $result->asText();
    }
}
