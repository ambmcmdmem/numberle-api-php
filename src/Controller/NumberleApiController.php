<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Controller\ComponentRegistry;
use App\Controller\Component\CollationComponent;
use App\Controller\Component\NumberleComponent;
use App\Controller\Component\NumberleConfigComponent;
use Cake\Http\Exception\BadRequestException;

/**
 * NumberleApi Controller
 *
 * @method \App\Model\Entity\NumberleApi[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NumberleApiController extends AppController
{
    /**
     * @var NumberleComponent $Numberle
     * @var CollationComponent $Collation
     * @var NumberleConfigComponent $NumberleConfig
     */
    private $Numberle;
    private $Collation;
    private $NumberleConfig;

    public function initialize(): void
    {
        if (
            Router::url() !== '/numberleApi/numberleConfig' &&
            (empty($this->request->getData('checkDigit')) ||
                (int)$this->request->getData('checkDigit') !== 1234509876 * (int)$this->request->getData('seed'))
        )
            throw new BadRequestException('不正なリクエストです。');

        parent::initialize();
        $this->Numberle = new NumberleComponent(new ComponentRegistry());
        $this->Collation = new CollationComponent(new ComponentRegistry());
        $this->NumberleConfig = new NumberleConfigComponent(new ComponentRegistry());
        $this->viewBuilder()->setClassName('Json');
    }

    public function validateSeed(): void
    {
        $this->Numberle->validateSeed((int)$this->request->getData('seed'));
        $this->set('seedValid', true);
        $this->viewBuilder()->setOption('serialize', ['seedValid']);
    }

    public function collation(): void
    {
        $this->set(
            'collation',
            $this->Collation->statusOfProposedSolution(
                $this->request->getData('proposedSolution'),
                $this->Numberle->getAnswer((int)$this->request->getData('seed'))
            )
        );
        $this->viewBuilder()->setOption('serialize', ['collation']);
    }

    public function answer(): void
    {
        $this->set('answer', $this->Numberle->getAnswer((int)$this->request->getData('seed')));
        $this->viewBuilder()->setOption('serialize', ['answer']);
    }

    public function numberleConfig(): void
    {
        $this->set('numberleConfig', [
            'maxNumberOfTries' => $this->NumberleConfig->getMaxNumberOfTries(),
            'maxNumberOfInput' => $this->NumberleConfig->getMaxNumberOfInput(),
        ]);
        $this->viewBuilder()->setOption('serialize', ['numberleConfig']);
    }
}
