<?php

namespace App\Livewire;

use App\Support\MarkdownToSpipConverter;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MarkdownToSpipPage extends Component
{
    public string $markdown = '';
    public string $spip = '';

    public function updatedMarkdown(): void
    {
        $this->spip = MarkdownToSpipConverter::convert($this->markdown);
    }

    public function render()
    {
        return view('livewire.markdown-to-spip-page');
    }
}
