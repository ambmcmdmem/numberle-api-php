<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use phpDocumentor\Reflection\Types\Callable_;
use PHPUnit\Framework\Constraint\Callback;

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

    private $maxNumberOfTries = 5;
    private $maxNumberOfInput = 5;

    public function getMaxNumberOfTries(): int
    {
        return $this->maxNumberOfTries;
    }

    public function getMaxNumberOfInput(): int
    {
        return $this->maxNumberOfInput;
    }
}
