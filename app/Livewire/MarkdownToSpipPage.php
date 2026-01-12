<?php

namespace App\Livewire;

use App\Support\MarkdownToSpipConverter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MarkdownToSpipPage extends Component
{
    public const MAX_LENGTH = 100000; // 100KB

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
        $key = 'markdown-timestamps:'.request()->ip();
        $timestamps = Cache::get($key, []);
        $now = time();
        $oneMinuteAgo = $now - 60;

        // Compter les requêtes de la dernière minute (60 secondes glissantes)
        $recentRequests = array_filter($timestamps, fn ($ts) => $ts > $oneMinuteAgo);

        return count($recentRequests);
    }

    public function updatedMarkdown(): void
    {
        // Validation taille
        if (mb_strlen($this->markdown) > self::MAX_LENGTH) {
            $this->spip = 'Texte trop long (maximum '.number_format(self::MAX_LENGTH, 0, ',', ' ').' caractères).';

            return;
        }

        // Rate limiting: 300 conversions per minute per IP (avec debounce 50ms côté front)
        $key = 'markdown-convert:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 300)) {
            $this->spip = 'Trop de requêtes. Veuillez patienter quelques secondes.';

            return;
        }

        RateLimiter::hit($key, 60);

        // Tracking timestamps pour compteur glissant (60 secondes)
        $timestampKey = 'markdown-timestamps:'.request()->ip();
        $timestamps = Cache::get($timestampKey, []);
        $now = time();

        // Ajouter le timestamp actuel
        $timestamps[] = $now;

        // Nettoyer les timestamps trop anciens (> 60 secondes)
        $timestamps = array_filter($timestamps, fn ($ts) => $ts > ($now - 60));

        // Stocker dans le cache pour 70 secondes
        Cache::put($timestampKey, array_values($timestamps), 70);

        $this->spip = MarkdownToSpipConverter::convert($this->markdown);
    }

    public function render()
    {
        return view('livewire.markdown-to-spip-page');
    }
}
