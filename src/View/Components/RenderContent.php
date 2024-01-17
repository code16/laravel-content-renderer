<?php

namespace Code16\ContentRenderer\View\Components;

use Code16\ContentRenderer\Support\Fragment;
use Code16\ContentRenderer\Support\FragmentsFactory;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class RenderContent extends Component
{
    public function __construct(
        public string $content,
        public FragmentsFactory $fragmentsFactory
    ) {
    }

    public function fragments(): Collection
    {
        return $this->fragmentsFactory->fromHTML(trim($this->content));
    }

    public function fragmentComponent(Fragment $fragment): ?string
    {
        if ($fragment->type === 'html') {
            return 'content::render-html';
        }
        if ($fragment->type === 'component') {
            return 'content::render-component';
        }

        return null;
    }

    public function render(): View
    {
        return view('content::components.render-content');
    }
}
