<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing\App\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;
use Riesenia\Routing\Attribute\Connect;
use Riesenia\Routing\Attribute\Resources;

#[Resources(only: ['view'])]
class ItemsController extends Controller
{
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    #[Connect(uri: 'cool-item')]
    public function index()
    {
        $this->set('data', $this->fetchTable()->find()->all());
        $this->viewBuilder()->setOption('serialize', 'data');
    }

    #[Connect(uri: '/custom-item')]
    public function custom()
    {
        $this->set('data', $this->fetchTable()->find()->all());
        $this->viewBuilder()->setOption('serialize', 'data');
    }

    #[Connect]
    public function noUriName()
    {
        $this->set('data', $this->fetchTable()->find()->all());
        $this->viewBuilder()->setOption('serialize', 'data');
    }

    public function view($id)
    {
        $this->set('data', $this->fetchTable()->get($id));
        $this->viewBuilder()->setOption('serialize', 'data');
    }
}
