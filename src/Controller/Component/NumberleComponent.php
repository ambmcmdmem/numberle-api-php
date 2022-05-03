<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\NumberleConfigComponent;
use \SeedValidations;


/**
 * Numberle component
 */

class NumberleComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

    private $numberleComponent;
    private $seedValidations;
    /**
     *
     * x, y, z, wはXorShiftアルゴリズム実行のためのパラメータ
     * https://www.jstatsoft.org/article/view/v008i14
     */
    private $x;
    private $y;
    private $z;
    private $w;

    public function initialize(array $config): void
    {
        $this->numberleComponent = new NumberleConfigComponent(new ComponentRegistry());
        $this->seedValidations = SeedValidations::getInstance()->getValidations();
    }

    private function xorshift(): int
    {
        $tmp = $this->x ^ ($this->x << 11);
        $this->x = $this->y;
        $this->y = $this->z;
        $this->z = $this->w;
        return ($this->w = $this->w ^ ($this->w >> 19) ^ ($tmp ^ ($tmp >> 8)));
    }

    private function shuffleReversibly(array $target): array
    {
        return collection(range(count($target) - 1, 1))
            ->reduce(function (array $toBeShuffled, int $i): array {
                $j = floor(abs($this->xorshift())) % ($i + 1);
                [$toBeShuffled[$i], $toBeShuffled[$j]] = [$toBeShuffled[$j], $toBeShuffled[$i]];
                return $toBeShuffled;
            }, $target);
    }

    public function validateSeed(int $seed): void
    {
        $this->seedValidations->validate([
            'seed' => $seed
        ]);
    }

    public function getAnswer(int $seed): string
    {
        $this->x = 31415926535;
        $this->y = 8979323846;
        $this->z = 2643383279;
        $this->w = $seed;

        return implode(
            collection($this->shuffleReversibly(range(0, 9)))
                ->take($this->numberleComponent->getMaxNumberOfInput())
                ->toArray()
        );
    }
}
