<?php

namespace App\Livewire;

use App\Support\MarkdownToSpipConverter;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MarkdownToSpipPage extends Component
{
    public const MAX_LENGTH = 100000; // 100KB

    public const MAX_ATTEMPTS = 300; // Requêtes par minute

    public string $markdown = '';

    public string $spip = '';

    #[Computed]
    public function characterCount(): int
    {
        return mb_strlen($this->markdown);
    }

    #[Computed]
    public function requestCount(): int
    {
        $key = 'markdown-convert:'.request()->ip();

        // Calculer le nombre de requêtes effectuées : max - remaining
        $remaining = RateLimiter::remaining($key, self::MAX_ATTEMPTS);

        return self::MAX_ATTEMPTS - $remaining;
    }

    public function updatedMarkdown(): void
    {
        // Validation taille
        if (mb_strlen($this->markdown) > self::MAX_LENGTH) {
            $this->spip = 'Texte trop long (maximum '.number_format(self::MAX_LENGTH, 0, ',', ' ').' caractères).';

            return;
        }

        // Rate limiting: MAX_ATTEMPTS conversions per minute per IP (avec debounce 50ms côté front)
        $key = 'markdown-convert:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $this->spip = 'Trop de requêtes ('.self::MAX_ATTEMPTS.'/min). Veuillez patienter quelques secondes.';

            return;
        }

        // Incrémenter le compteur RateLimiter (expire après 60 secondes)
        RateLimiter::hit($key, 60);

        $this->spip = MarkdownToSpipConverter::convert($this->markdown);
    }

    public function render()
    {
        return view('livewire.markdown-to-spip-page');
    }
}
