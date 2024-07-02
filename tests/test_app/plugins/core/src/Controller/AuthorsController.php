<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Core\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;
use Riesenia\Core\Attribute\CoreResources;
use Riesenia\Routing\Attribute\Connect;

#[CoreResources(only: ['view', 'index'])]
class AuthorsController extends Controller
{
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    public function view($id)
    {
        $this->set('data', $this->fetchTable()->get($id));
        $this->viewBuilder()->setOption('serialize', 'data');
    }

    public function index()
    {
        $this->set('data', $this->fetchTable()->find()->all());
        $this->viewBuilder()->setOption('serialize', 'data');
    }

    #[Connect(scope: '/admin', plugin: 'Riesenia/Core', uri: '{id}', defaults: ['_method' => 'DELETE'], options: ['id' => '[0-9]', 'pass' => ['id']])]
    public function delete($id)
    {
        $this->fetchTable()->delete($this->fetchTable()->get($id));
        $this->set('data', ['message' => 'Deleted']);
        $this->viewBuilder()->setOption('serialize', 'data');
    }
}
