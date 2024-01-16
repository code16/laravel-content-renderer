<?php

namespace Code16\LaravelContentRenderer\Support;

class HTMLFragment extends Fragment
{
    public string $type = 'html';

    protected string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getHTML(): string
    {
        return $this->content;
    }

    public static function fromDOMNode(\DOMNode $node): static
    {
        $html = $node->ownerDocument->saveHTML($node);

        return new static($html);
    }
}
