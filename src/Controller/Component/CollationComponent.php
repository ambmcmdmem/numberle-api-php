<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use \CollationException;

/**
 * Collation component
 */

function statusPattern(bool $condition, string $status): array
{

    if (
        !collection(['correct', 'differentLocation', 'wrong'])
            ->contains($status)
    )
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

        return collection(str_split($proposedSolution))
            ->map(function (string $proposedSolutionCharacter, int $proposedSolutionCharacterNo): string {
                return collection([
                    statusPattern($proposedSolutionCharacter === substr($this->answer, $proposedSolutionCharacterNo, 1), 'correct'),
                    statusPattern(strpos($this->answer, $proposedSolutionCharacter) !== false, 'differentLocation'),
                    statusPattern(true, 'wrong')
                ])->firstMatch([
                    'condition' => true
                ])['status'];
            })->toArray();
    }
}
