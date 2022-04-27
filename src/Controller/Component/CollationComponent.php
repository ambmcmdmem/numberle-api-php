<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Collation component
 */

class CollationComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];
    private $answer;

    public function initialize(array $config): void
    {
        $this->answer = $config['answer'];
    }

    public function statusOfProposedSolution(string $proposedSolution): array
    {
        if (strlen($proposedSolution) !== strlen($this->answer))
            throw new \Exception('提示された文字列長と回答の文字列長が異なります。');

        return array_map(
            function (string $proposedSolutionCharacter, int $proposedSolutionCharacterNo): string {
                if ($proposedSolutionCharacter === substr($this->answer, $proposedSolutionCharacterNo, 1))
                    return 'correct';
                else if (strpos($this->answer, $proposedSolutionCharacter) !== false)
                    return 'differentLocation';
                else
                    return 'wrong';
            },
            str_split($proposedSolution),
            range(0, strlen($proposedSolution) - 1)
        );
    }
}
