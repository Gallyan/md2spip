<?php

namespace App\Livewire;

use App\Support\MarkdownToSpipConverter;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MarkdownToSpipPage extends Component
{
    public string $markdown = '';
    public string $spip = '';

    public function updatedMarkdown(): void
    {
        // Rate limiting: 300 conversions per minute per IP (avec debounce 200ms côté front)
        $key = 'markdown-convert:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 300)) {
            $this->spip = 'Trop de requêtes. Veuillez patienter quelques secondes.';
            return;
        }

        RateLimiter::hit($key, 60);

        $this->spip = MarkdownToSpipConverter::convert($this->markdown);
    }

    public function render()
    {
        return view('livewire.markdown-to-spip-page');
    }
}
