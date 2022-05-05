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
final class NumberleApiControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [];

    final public function testAccessValidateSeedWithoutData(): void
    {
        $this->get('/numberleApi/validateSeed');
        $this->assertResponseCode(400, 'validateSeedへアクセスした際、エラーを送出しません。');
        $this->assertResponseContains('不正なリクエストです。');
    }

    final public function testAccessValidateSeedWithCorrectData(): void
    {
        $this->post('/numberleApi/validateSeed', [
            'seed' => '1',
            'checkDigit' => '1234509876'
        ]);
        $this->assertResponseOk('シード、checkDigitらが正しいのにvalidateSeedへアクセスできません。');
    }

    final public function testAccessValidateSeedWithWrongData(): void
    {
        $this->post('/numberleApi/validateSeed', [
            'seed' => '0',
            'checkDigit' => '0'
        ]);
        $this->assertResponseCode(400, 'シード、checkDigitらに不正な値が入っているのにエラーを送出しません。');
        $this->assertResponseContains('不正なリクエストです。');
    }

    final public function testAccessCollationWithCorrectData(): void
    {
        $this->post('/numberleApi/collation', [
            'seed' => '1',
            'checkDigit' => '1234509876',
            'proposedSolution' => '12345'
        ]);
        $this->assertResponseOk('適切な値を受け渡しているにもかかわらず、collationへとアクセスすることができません。');
    }

    final public function testAccessAnswerWithCorrectData(): void
    {
        $this->post('/numberleApi/answer', [
            'seed' => '1',
            'checkDigit' => '1234509876',
            'numberOfTries' => '3'
        ]);
        $this->assertResponseOk('適切な値を受け渡しているにもかかわらず、answerへとアクセスすることができません。');
    }

    final public function testAccessAnswerWithIncorrectNumberOfTriesData(): void
    {
        $this->post('/numberleApi/answer', [
            'seed' => '1',
            'checkDigit' => '1234509876',
            'numberOfTries' => '-1231231'
        ]);
        $this->assertResponseCode(400, 'numberOfTriesに不正な値が入っているのにエラーを送出しません。');
        $this->assertResponseContains('不正なリクエストです。');
    }

    final public function testAccessTotallingWithCorrectData(): void
    {
        $this->post('/numberleApi/totalling', [
            'seed' => '1',
            'checkDigit' => '1234509876',
        ]);
        $this->assertResponseOk('適切な値を受け渡しているにもかかわらず、totallingへとアクセスすることができません。');
    }
}
