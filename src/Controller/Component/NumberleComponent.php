<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use \SeedException;
use App\Controller\Component\NumberleConfigComponent;

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

    /**
     *
     * x, y, z, wはXorShiftアルゴリズム実行のためのパラメータ
     * https://www.jstatsoft.org/article/view/v008i14
     */
    private $x;
    private $y;
    private $z;
    private $w;

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
        return array_reduce(array_reverse(range(1, count($target) - 1)), function (array $toBeShuffled, int $i): array {
            $j = floor(abs($this->xorshift())) % ($i + 1);
            [$toBeShuffled[$i], $toBeShuffled[$j]] = [$toBeShuffled[$j], $toBeShuffled[$i]];
            return $toBeShuffled;
        }, $target);
    }

    public function validateSeed(int $seed): void
    {
        validate([
            pattern(
                $seed > 0,
                new SeedException('シードが0以下の値になっています。', 500)
            ),
            pattern(
                $seed <= 1000,
                new SeedException('シードが1000より大きな値になっています。', 500)
            )
        ]);
    }

    public function getAnswer(int $seed): string
    {
        $this->x = 31415926535;
        $this->y = 8979323846;
        $this->z = 2643383279;
        $this->w = $seed;

        $numberleComponent = new NumberleConfigComponent(new ComponentRegistry());
        return implode(
            array_slice(
                $this->shuffleReversibly(range(0, 9)),
                0,
                $numberleComponent->getMaxNumberOfInput()
            )
        );
    }
}
