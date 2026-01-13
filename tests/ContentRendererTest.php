<?php

namespace Code16\ContentRenderer\Tests;

use Code16\ContentRenderer\Support\ComponentFragment;
use Code16\ContentRenderer\Support\FragmentsFactory;
use Code16\ContentRenderer\Support\HTMLFragment;
use Illuminate\Support\Facades\Blade;

it('parses simple HTML without components', function () {
    $factory = new FragmentsFactory();
    $fragments = $factory->fromHTML('<p>Hello world</p>');

    expect($fragments)->toHaveCount(1)
        ->and($fragments->first())->toBeInstanceOf(HTMLFragment::class)
        ->and($fragments->first()->getHTML())->toBe('<p>Hello world</p>');
});

it('parses a single component', function () {
    $factory = new FragmentsFactory();
    $fragments = $factory->fromHTML('<x-test-component />');

    expect($fragments)->toHaveCount(1)
        ->and($fragments->first())->toBeInstanceOf(ComponentFragment::class)
        ->and($fragments->first()->getComponentName())->toBe('test-component');
});

it('parses mixed HTML and components', function () {
    $factory = new FragmentsFactory();
    $fragments = $factory->fromHTML('<p>Before</p><x-test-component /><p>After</p>');

    expect($fragments)->toHaveCount(3)
        ->and($fragments[0])->toBeInstanceOf(HTMLFragment::class)
        ->and($fragments[0]->getHTML())->toBe('<p>Before</p>')
        ->and($fragments[1])->toBeInstanceOf(ComponentFragment::class)
        ->and($fragments[1]->getComponentName())->toBe('test-component')
        ->and($fragments[2])->toBeInstanceOf(HTMLFragment::class)
        ->and($fragments[2]->getHTML())->toBe('<p>After</p>');
});

it('parses component attributes', function () {
    $factory = new FragmentsFactory();
    $fragments = $factory->fromHTML('<x-test-component title="Hello" :active="true" />');

    /** @var ComponentFragment $fragment */
    $fragment = $fragments->first();
    expect($fragment->getComponentAttributes())->toBe([
        'title' => 'Hello',
        ':active' => 'true',
    ]);
});

it('parses component content', function () {
    $factory = new FragmentsFactory();
    $fragments = $factory->fromHTML('<x-test-component><p>Inner content</p></x-test-component>');

    /** @var ComponentFragment $fragment */
    $fragment = $fragments->first();
    expect($fragment->content)->toBe('<p>Inner content</p>');
});

it('groups consecutive HTML fragments', function () {
    // This tests the groupFragments logic in FragmentsFactory
    // FragmentsFactory::fromDOMNode returns a fragment for EACH node.
    // DOMDocument might have text nodes between elements.
    $factory = new FragmentsFactory();
    $fragments = $factory->fromHTML('Part 1 <strong>Part 2</strong> Part 3');

    expect($fragments)->toHaveCount(1)
        ->and($fragments->first())->toBeInstanceOf(HTMLFragment::class)
        ->and($fragments->first()->getHTML())->toBe('Part 1 <strong>Part 2</strong> Part 3');
});

it('handles components wrapped in other tags', function() {
    $factory = new FragmentsFactory();
    // FragmentsFactory::findComponentElement also checks $node->firstChild
    // This is useful if the HTML parser wraps the component in a <p> if it's not a block element,
    // although <x- is unknown to it.
    $fragments = $factory->fromHTML('<p><x-test-component /></p>');

    expect($fragments)->toHaveCount(1)
        ->and($fragments->first())->toBeInstanceOf(ComponentFragment::class)
        ->and($fragments->first()->getComponentName())->toBe('test-component');
});

it('renders content with components', function () {
    Blade::component('test-component', \Code16\ContentRenderer\Tests\TestComponent::class);

    $content = '<p>Some text</p><x-test-component title="My Title" /><p>More text</p>';

    $rendered = Blade::render(
        '<x-content::render-content :content="$content" :fragments-factory="app(\Code16\ContentRenderer\Support\FragmentsFactory::class)" />',
        ['content' => $content]
    );

    expect($rendered)
        ->toContain('<p>Some text</p>')
        ->toContain('<div>Component: My Title')
        ->toContain('<p>More text</p>');
});

it('renders content with components inside a Content component', function () {
    Blade::component('test-component', \Code16\ContentRenderer\Tests\TestComponent::class);
    Blade::component('content', \Code16\ContentRenderer\Tests\TestContent::class);

    $content = '<x-test-component title="My Title" />';

    $rendered = Blade::render(
        '<x-content>{!! $content !!}</x-content>',
        ['content' => $content]
    );

    expect($rendered)
        ->toContain('<div>Component: My Title');
});

it('allows Content component to provide extra attributes', function () {
    Blade::component('test-component', \Code16\ContentRenderer\Tests\TestComponentWithExtra::class);
    Blade::component('content', \Code16\ContentRenderer\Tests\TestContentWithAttributes::class);

    $content = '<x-test-component title="Base Title" />';

    $rendered = Blade::render(
        '<x-content>{!! $content !!}</x-content>',
        ['content' => $content]
    );

    expect($rendered)
        ->toContain('<div>Component: Base Title (Extra: from-content)</div>');
});

it('handles multiple components of the same type', function() {
    $factory = new FragmentsFactory();
    $fragments = $factory->fromHTML('<x-test-component id="1" /><x-test-component id="2" />');

    expect($fragments)->toHaveCount(2)
        ->and($fragments[0]->getComponentName())->toBe('test-component')
        ->and($fragments[0]->getComponentAttributes()['id'])->toBe('1')
        ->and($fragments[1]->getComponentName())->toBe('test-component')
        ->and($fragments[1]->getComponentAttributes()['id'])->toBe('2');
});

it('handles components with special characters in attributes', function() {
    $factory = new FragmentsFactory();
    $fragments = $factory->fromHTML('<x-test-component title="L\'été est là & &quot;frais&quot;" />');

    /** @var ComponentFragment $fragment */
    $fragment = $fragments->first();
    // DOMDocument might decode entities depending on how it's handled
    expect($fragment->getComponentAttributes()['title'])->toBe('L\'été est là & "frais"');
});

class TestComponent extends \Illuminate\View\Component
{
    public function __construct(public string $title) {}
    public function render() { return '<div>Component: {{ $title }}</div>'; }
}

class TestComponentWithExtra extends \Illuminate\View\Component
{
    public function __construct(public string $title, public string $extra = 'default') {}
    public function render() { return '<div>Component: {{ $title }} (Extra: {{ $extra }})</div>'; }
}

class TestContent extends \Code16\ContentRenderer\View\Components\Content
{
}

class TestContentWithAttributes extends \Code16\ContentRenderer\View\Components\Content
{
    public function __construct()
    {
        parent::__construct();
        $this->contentComponentAttributes->put('test-component', ['extra' => 'from-content']);
    }
}
