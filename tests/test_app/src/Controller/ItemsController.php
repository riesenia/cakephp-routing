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
use Riesenia\Routing\Attributes\Resource;
use Riesenia\Routing\Attributes\Route;

#[Resource(only: ['view'])]
class ItemsController extends Controller
{
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    #[Route(uri: 'cool-item')]
    public function index()
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
