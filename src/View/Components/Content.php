<?php

namespace Code16\ContentRenderer\View\Components;


use Code16\ContentRenderer\Support\ComponentAttributeBagCollection;
use Illuminate\View\Component;

abstract class Content extends Component
{
    public ComponentAttributeBagCollection $contentComponentAttributes;
    public self $contentComponent;

    public function __construct() {
        $this->contentComponentAttributes = new ComponentAttributeBagCollection();
        $this->contentComponent = $this;
    }

    public function render(): string
    {
        return '<x-content::render-content :content="$slot" />';
    }
}
