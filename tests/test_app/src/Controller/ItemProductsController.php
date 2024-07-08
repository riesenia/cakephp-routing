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

#[Resources]
class ItemProductsController extends Controller
{
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    public function index()
    {
        $this->set('data', $this->fetchTable()->find()->all());
        $this->viewBuilder()->setOption('serialize', 'data');
    }

    #[Connect(uri: '/custom-index', defaults: ['_method' => 'GET'])]
    public function customIndex()
    {
        $this->set('data', $this->fetchTable()->find()->all());
        $this->viewBuilder()->setOption('serialize', 'data');
    }

    public function view($id)
    {
        $this->set('data', $this->fetchTable()->get($id));
        $this->viewBuilder()->setOption('serialize', 'data');
    }

    #[Connect(defaults: ['_method' => 'PATCH', '_ext' => 'json'])]
    public function add()
    {
        $data = 'added';
        $this->set(\compact('data'));
        $this->viewBuilder()->setOption('serialize', 'data');
    }
}
