<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Component\NumberleConfigComponent;
use Cake\Controller\ComponentRegistry;

/**
 * NumberleConfigApi Controller
 *
 * @method \App\Model\Entity\NumberleConfigApi[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
final class NumberleConfigApiController extends AppController
{
    private NumberleConfigComponent $NumberleConfig;

    final public function initialize(): void
    {
        parent::initialize();
        $this->NumberleConfig = new NumberleConfigComponent(new ComponentRegistry());
        $this->viewBuilder()->setClassName('Json');
    }

    final public function index()
    {
        $this->set('numberleConfig', [
            'maxNumberOfTries' => $this->NumberleConfig->getMaxNumberOfTries(),
            'maxNumberOfInput' => $this->NumberleConfig->getMaxNumberOfInput(),
        ]);
        $this->viewBuilder()->setOption('serialize', ['numberleConfig']);
    }
}
