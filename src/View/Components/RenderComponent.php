<?php

namespace Code16\LaravelContentRenderer\View\Components;

use Code16\LaravelContentRenderer\Support\ComponentFragment;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;

class RenderComponent extends Component
{
    public function __construct(
        public ComponentFragment $fragment,
    ) {
    }

    public function resolveAttributes(Content $content): ComponentAttributeBag
    {
        $componentName = $this->fragment->getComponentName();
        $attributes = new ComponentAttributeBag($this->fragment->getComponentAttributes());

        if ($contentAttributes = $content->contentComponentAttributes->get($componentName)) {
            $attributes = $attributes->merge($contentAttributes->getAttributes());
        }

        return $attributes;
    }

    public function render(): View
    {
        return view('content::components.render-component');
    }
}
