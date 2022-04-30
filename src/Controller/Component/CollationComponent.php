<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use phpDocumentor\Reflection\Types\Callable_;
use \CollationException;

/**
 * Collation component
 */

function pattern(bool $condition, string $status): array
{
    $allStatus = ['correct', 'differentLocation', 'wrong'];

    if (!in_array($status, $allStatus))
        throw new CollationException('pattern関数の引数のステータスにおかしい値が入っています。');

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
    private $proposedSolution;
    private $answer;

    public function initialize(array $config): void
    {
        $this->proposedSolution = $config['proposedSolution'];
    }

    public function statusOfProposedSolution(string $answer): array
    {
        if (
            $this->proposedSolution &&
            $answer &&
            strlen($answer) !== strlen($this->proposedSolution)
        )
            throw new CollationException('提示された文字列長と回答の文字列長が異なります。');

        $this->answer = $answer;

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
