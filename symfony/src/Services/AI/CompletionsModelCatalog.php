<?php
namespace App\Services\AI;

use AllowDynamicProperties;
use Symfony\AI\Platform\ModelCatalog\AbstractModelCatalog;
use Symfony\AI\Platform\Capability;
use Symfony\AI\Platform\Model;

#[AllowDynamicProperties]
class CompletionsModelCatalog extends AbstractModelCatalog
{
    public function __construct()
    {
        $this->models = [];
    }

    public function getModel(string $modelName): Model
    {
        $parsed = self::parseModelName($modelName);

        return new \Symfony\AI\Platform\Bridge\Ollama\Ollama(
            $parsed['name'],
            Capability::cases(),
            $parsed['options']
        );
    }
}
