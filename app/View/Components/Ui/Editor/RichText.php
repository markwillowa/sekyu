<?php

namespace App\View\Components\Ui\Editor;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RichText extends Component
{
    public function __construct(
        public string $name,
        public string $label = '',
        public ?string $value = null,
        public ?string $placeholder = null,
        public bool $required = false,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.ui.editor.rich-text');
    }
}
