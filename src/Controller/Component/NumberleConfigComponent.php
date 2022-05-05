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
    private int $seedLowerLimit = 1;
    private int $seedUpperLimit = 1000;

    final public function getMaxNumberOfTries(): int
    {
        return $this->maxNumberOfTries;
    }

    final public function getMaxNumberOfInput(): int
    {
        return $this->maxNumberOfInput;
    }

    final public function getSeedLowerLimit(): int
    {
        return $this->seedLowerLimit;
    }

    final public function getSeedUpperLimit(): int
    {
        return $this->seedUpperLimit;
    }
}
