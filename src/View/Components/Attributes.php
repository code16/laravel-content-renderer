<?php

namespace Code16\LaravelContentRenderer\View\Components;

use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;

abstract class Attributes extends Component
{
    public function __construct(
        public string $component,
    ) {
    }

    public function addAttributes(Content $content, ComponentAttributeBag $attributes): void
    {
        $content->contentComponentAttributes->put(
            $this->component,
            $attributes,
        );
    }

    public function render(): View
    {
        return view('content::components.attributes');
    }
}
