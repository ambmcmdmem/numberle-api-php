<?php

declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\NumberleApiController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\NumberleApiController Test Case
 *
 * @uses \App\Controller\NumberleApiController
 */
class NumberleApiControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [];

    // public function testAccessNumberleConfigWithoutData(): void
    // {
    //     $this->post('/numberleApi/numberleConfig');
    //     $this->assertResponseOk('numberleConfigにアクセスできません。');
    // }

    public function testAccessValidateSeedWithoutData(): void
    {
        $this->get('/numberleApi/validateSeed');
        $this->assertResponseCode(500, 'validateSeedへアクセスした際、エラーを送出しません。');
        $this->assertResponseContains('You cannot connect.');
    }

    public function testAccessValidateSeedWithCorrectData(): void
    {
        $this->post('/numberleApi/validateSeed', [
            'seed' => '1',
            'checkDigit' => '1234509876'
        ]);
        $this->assertResponseOk('シード、checkDigitらが正しいのにvalidateSeedへアクセスできません。');
    }

    public function testAccessValidateSeedWithWrongData(): void
    {
        $this->post('/numberleApi/validateSeed', [
            'seed' => '0',
            'checkDigit' => '0'
        ]);
        $this->assertResponseCode(500, 'シード、checkDigitらがおかしいのにエラーを送出しません。');
        $this->assertResponseContains('You cannot connect.');
    }

    public function testAccessCollationWithCorrectData(): void
    {
        $this->post('/numberleApi/collation', [
            'seed' => '1',
            'checkDigit' => '1234509876',
            'proposedSolution' => '12345'
        ]);
        $this->assertResponseOk('適切な値を受け渡しているにもかかわらず、collationへとアクセスすることができません。');
    }

    public function testAccessAnswerWithCorrectData(): void
    {
        $this->post('/numberleApi/answer', [
            'seed' => '1',
            'checkDigit' => '1234509876'
        ]);
        $this->assertResponseOk('適切な値を受け渡しているにもかかわらず、answerへとアクセスすることができません。');
    }

    public function testBelowSeed(): void
    {
        $this->post('/numberleApi/validateSeed', [
            'seed' => '-1',
            'checkDigit' => '-1234509876'
        ]);
        $this->assertResponseCode(400, 'シードが0以下の値であるのにエラーを送出しません。');
        $this->assertResponseContains('シードが適当な値でありません。');
    }

    public function testExceedSeed(): void
    {
        $this->post('/numberleApi/validateSeed', [
            'seed' => '1001',
            'checkDigit' => (string)1234509876 * 1001
        ]);
        $this->assertResponseCode(400, 'シードが1000より大きい値であるのにエラーを送出しません。');
        $this->assertResponseContains('シードが適当な値でありません。');
    }

    public function testSeedIsDouble(): void
    {
        $this->post('/numberleApi/validateSeed', [
            'seed' => '1.5',
            'checkDigit' => (string)1234509876 * 1.5
        ]);
        $this->assertResponseCode(500, 'シードが整数でないのにエラーを送出しません。');
        $this->assertResponseContains('シードが適当な値でありません。');
    }
}
