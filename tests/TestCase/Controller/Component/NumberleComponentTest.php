<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\NumberleComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\NumberleComponent Test Case
 */
class NumberleComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\NumberleComponent
     */
    protected $Numberle;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Numberle = new NumberleComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Numberle);

        parent::tearDown();
    }
}
