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
class NumberleConfigApiController extends AppController
{
    private NumberleConfigComponent $NumberleConfig;

    public function initialize(): void
    {
        parent::initialize();
        $this->NumberleConfig = new NumberleConfigComponent(new ComponentRegistry());
        $this->viewBuilder()->setClassName('Json');
    }

    public function index()
    {
        $this->set('numberleConfig', [
            'maxNumberOfTries' => $this->NumberleConfig->getMaxNumberOfTries(),
            'maxNumberOfInput' => $this->NumberleConfig->getMaxNumberOfInput(),
        ]);
        $this->viewBuilder()->setOption('serialize', ['numberleConfig']);
    }
}
