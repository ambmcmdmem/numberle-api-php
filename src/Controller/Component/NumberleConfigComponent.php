<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

/**
 * NumberleConfig component
 */
final class NumberleConfigComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

    private int $maxNumberOfTries = 5;
    private int $maxNumberOfInput = 5;

    final public function getMaxNumberOfTries(): int
    {
        return $this->maxNumberOfTries;
    }

    final public function getMaxNumberOfInput(): int
    {
        return $this->maxNumberOfInput;
    }
}
