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

function statusPattern(bool $condition, string $status): array
{
    $allStatus = ['correct', 'differentLocation', 'wrong'];

    if (!in_array($status, $allStatus))
        throw new CollationException('pattern関数の引数のステータスに想定されていない値が入っています。', 500);

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

    public function statusOfProposedSolution(string $proposedSolution, string $answer): array
    {
        validate([
            pattern(
                (bool)$proposedSolution,
                new CollationException('提案された文字列が空です。', 500)
            ),
            pattern(
                (bool)$answer,
                new CollationException('回答が空です。', 500)
            ),
            pattern(
                strlen($answer) === strlen($proposedSolution),
                new CollationException('提示された文字列の長さと回答の文字列長が異なります。', 500)
            )
        ]);

        $this->answer = $answer;

        return array_map(
            function (string $proposedSolutionCharacter, int $proposedSolutionCharacterNo): string {
                $conditionAndStatus = [
                    statusPattern($proposedSolutionCharacter === substr($this->answer, $proposedSolutionCharacterNo, 1), 'correct'),
                    statusPattern(strpos($this->answer, $proposedSolutionCharacter) !== false, 'differentLocation'),
                    statusPattern(true, 'wrong')
                ];
                return $conditionAndStatus[array_search(true, array_column($conditionAndStatus, 'condition'))]['status'];
            },
            str_split($proposedSolution),
            range(0, strlen($proposedSolution) - 1)
        );
    }
}
