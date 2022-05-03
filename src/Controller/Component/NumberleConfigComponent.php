<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;

/**
 * NumberleConfig component
 */
class NumberleConfigComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

    private int $maxNumberOfTries = 5;
    private int $maxNumberOfInput = 5;

    public function getMaxNumberOfTries(): int
    {
        return $this->maxNumberOfTries;
    }

    public function getMaxNumberOfInput(): int
    {
        return $this->maxNumberOfInput;
    }
}
