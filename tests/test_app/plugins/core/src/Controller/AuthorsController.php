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

#[CoreResources(only: ['index'])]
class AuthorsController extends Controller
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
}
