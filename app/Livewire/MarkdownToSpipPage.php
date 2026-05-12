<?php

namespace App\Livewire;

use App\Support\MarkdownToSpipConverter;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
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
        // Stats : compter la session (une seule fois par session)
        if (! session()->has('stats_counted')) {
            $this->incrementStat('sessions');
            session()->put('stats_counted', true);
        }

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
     * Appelé lors du clic sur le bouton copier.
     * Comptabilise les stats de copie.
     */
    public function countCopy(): void
    {
        if (! session()->has('converted')) {
            $this->incrementStat('conversions');
            session()->put('converted', true);
        }

        $this->incrementStat('copies');
        $this->incrementStat('total_chars', mb_strlen($this->markdown));
    }

    /**
     * Incrémente une statistique dans le fichier JSON sous verrou exclusif
     * pour éviter les écritures concurrentes.
     */
    private function incrementStat(string $key, int $value = 1): void
    {
        $disk = Storage::disk('stats');
        $root = $disk->path('');
        if (! is_dir($root)) {
            mkdir($root, 0755, true);
        }

        $handle = fopen($disk->path('stats.json'), 'c+');
        if ($handle === false) {
            return;
        }

        try {
            if (! flock($handle, LOCK_EX)) {
                return;
            }

            $content = stream_get_contents($handle);
            $stats = $content ? json_decode($content, true) : [];
            $today = date('Y-m-d');
            $stats[$today][$key] = ($stats[$today][$key] ?? 0) + $value;

            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($stats, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
            fflush($handle);
            flock($handle, LOCK_UN);
        } finally {
            fclose($handle);
        }
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
