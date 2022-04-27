<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\CollationComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\CollationComponent Test Case
 */
class CollationComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\CollationComponent
     */
    protected $Collation;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Collation = new CollationComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Collation);

        parent::tearDown();
    }
}
