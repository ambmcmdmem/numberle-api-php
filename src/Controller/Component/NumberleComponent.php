<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

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

    private $answer;
    /**
     *
     * x, y, z, wはXorShiftアルゴリズム実行のためのパラメータ
     * https://www.jstatsoft.org/article/view/v008i14
     */
    private $x = 31415926535;
    private $y = 8979323846;
    private $z = 2643383279;
    private $w;

    public function initialize(array $config): void
    {
        $this->w = $config['seed'];
        $this->answer = implode(array_slice($this->shuffleReversibly(range(0, 9)), 0, 5));
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
        return array_reduce(array_reverse(range(1, count($target) - 1)), function (array $toBeShuffled, int $i): array {
            $j = floor(abs($this->xorshift())) % ($i + 1);
            [$toBeShuffled[$i], $toBeShuffled[$j]] = [$toBeShuffled[$j], $toBeShuffled[$i]];
            return $toBeShuffled;
        }, $target);
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }
}
