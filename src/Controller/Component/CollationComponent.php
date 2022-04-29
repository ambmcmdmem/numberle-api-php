<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use phpDocumentor\Reflection\Types\Callable_;

/**
 * Collation component
 */

function pattern(bool $condition, string $status): array
{
    return [
        'condition' => $condition,
        'status' => $status
    ];
}

class CollationComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];
    private $answer;
    private $proposedSolution;

    public function initialize(array $config): void
    {
        if (
            $config['proposedSolution'] &&
            $config['answer'] &&
            strlen($config['answer']) !== strlen($config['proposedSolution'])
        )
            throw new \CollationException('提示された文字列長と回答の文字列長が異なります。');

        $this->answer = $config['answer'];
        $this->proposedSolution = $config['proposedSolution'];
    }

    public function statusOfProposedSolution(): array
    {
        return array_map(
            function (string $proposedSolutionCharacter, int $proposedSolutionCharacterNo): string {
                $conditionAndStatus = [
                    pattern($proposedSolutionCharacter === substr($this->answer, $proposedSolutionCharacterNo, 1), 'correct'),
                    pattern(strpos($this->answer, $proposedSolutionCharacter) !== false, 'differentLocation'),
                    pattern(true, 'wrong')
                ];
                return $conditionAndStatus[array_search(true, array_column($conditionAndStatus, 'condition'))]['status'];
            },
            str_split($this->proposedSolution),
            range(0, strlen($this->proposedSolution) - 1)
        );
    }
}
