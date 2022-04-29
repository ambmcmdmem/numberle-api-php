<?php

declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\NumberleConfigComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\NumberleConfigComponent Test Case
 */
class NumberleConfigComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\NumberleConfigComponent
     */
    protected $NumberleConfig;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->NumberleConfig = new NumberleConfigComponent($registry);
    }

    public function testReceiveProperty(): void
    {
        $this->assertEquals(5, $this->NumberleConfig->getMaxNumberOfTries());
        $this->assertEquals(5, $this->NumberleConfig->getMaxNumberOfInput());
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->NumberleConfig);

        parent::tearDown();
    }
}
