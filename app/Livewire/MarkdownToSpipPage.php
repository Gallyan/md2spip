<?php

namespace App\Livewire;

use App\Support\MarkdownToSpipConverter;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Composant Livewire de la page principale de conversion Markdown vers SPIP.
 *
 * Gère la conversion en temps réel avec validation de taille et rate limiting
 * pour protéger contre les abus (200 requêtes/minute, max 100KB de texte).
 */
#[Layout('layouts.app')]
class MarkdownToSpipPage extends Component
{
    public const MAX_LENGTH = 100000; // 100KB

    public const MAX_ATTEMPTS = 200; // Requêtes par minute

    public string $markdown = '';

    public string $spip = '';

    /**
     * Déclenché automatiquement à chaque modification du texte Markdown.
     *
     * Vérifie la taille du texte et le rate limiting avant de convertir.
     */
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

    /**
     * Rend la vue du composant Livewire.
     *
     * @return View Vue Livewire de la page de conversion
     */
    public function render(): View
    {
        return view('livewire.markdown-to-spip-page');
    }
}
