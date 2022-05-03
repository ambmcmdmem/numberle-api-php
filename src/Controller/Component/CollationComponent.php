<?php

declare(strict_types=1);

namespace App\Controller\Component\CollationComponent\_Private;

use \Validations;
use \Validation;
use \CollationException;

class ProposedSolutionValidations
{
    private static ProposedSolutionValidations $instance;
    private Validations $validations;

    private function __construct()
    {
        $this->validations = (new Validations())->next(
            new Validation(
                function (array $props): bool {
                    return (bool)$props['proposedSolution'];
                },
                new CollationException('解答案が空です。', 500)
            )
        )->next(
            new Validation(
                function (array $props): bool {
                    return (bool)$props['answer'];
                },
                new CollationException('解答が空です。', 500)
            )
        )->next(
            new Validation(
                function (array $props): bool {
                    return strlen($props['answer']) === strlen($props['proposedSolution']);
                },
                new CollationException('解答案の文字列長と解答の文字列長が異なります。', 500)
            )
        );
    }

    final public static function getValidations(): Validations
    {
        if (!isset(self::$instance))
            self::$instance = new ProposedSolutionValidations();

        return self::$instance->validations;
    }
}


namespace App\Controller\Component;

use Cake\Controller\Component;
use \CollationException;
use App\Controller\Component\CollationComponent\_Private\ProposedSolutionValidations;
use Cake\Collection\Collection;

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

    private Collection $all_status;
    private \Validations $proposedSolutionValidations;

    public function initialize(array $config): void
    {
        $this->all_status = collection(['correct', 'differentLocation', 'wrong']);
        $this->proposedSolutionValidations = ProposedSolutionValidations::getValidations();
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
