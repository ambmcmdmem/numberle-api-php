<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use \CollationException;
use \ProposedSolutionValidations;

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

    private $all_status;
    private $proposedSolutionValidations;

    public function initialize(array $config): void
    {
        $this->all_status = collection(['correct', 'differentLocation', 'wrong']);
        $this->proposedSolutionValidations = ProposedSolutionValidations::getInstance()->getValidations();
    }

    private function statusPattern(callable $condition, string $status): array
    {
        if (!$this->all_status->contains($status))
            throw new CollationException('pattern関数の引数のステータスに想定されていない値が入っています。', 500);

        return [
            'condition' => $condition,
            'status' => $status
        ];
    }

    public function statusOfProposedSolution(string $proposedSolution, string $answer): array
    {
        $this->proposedSolutionValidations->validate([
            'proposedSolution' => $proposedSolution,
            'answer' => $answer
        ]);

        return collection(str_split($proposedSolution))
            ->map(function (
                string $proposedSolutionCharacter,
                int $proposedSolutionCharacterNo
            ) use ($answer): string {
                return collection([
                    $this->statusPattern(
                        function () use ($proposedSolutionCharacter, $proposedSolutionCharacterNo, $answer): bool {
                            return $proposedSolutionCharacter ===
                                substr($answer, $proposedSolutionCharacterNo, 1);
                        },
                        'correct'
                    ),
                    $this->statusPattern(
                        function () use ($proposedSolutionCharacter, $answer): bool {
                            return strpos($answer, $proposedSolutionCharacter) !== false;
                        },
                        'differentLocation'
                    ),
                    $this->statusPattern(
                        function (): bool {
                            return true;
                        },
                        'wrong'
                    )
                ])->filter(function (array $conditionAndStatus): bool {
                    return $conditionAndStatus['condition']();
                })->first()['status'];
            })->toArray();
    }
}
