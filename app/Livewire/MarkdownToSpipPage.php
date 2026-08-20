<?php

namespace App\Livewire;

use App\Support\MarkdownToSpipConverter;
use App\Support\Stats;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Number;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Livewire component for the main Markdown-to-SPIP conversion page.
 *
 * Handles real-time conversion with size validation and rate limiting
 * to protect against abuse (200 requests/minute, max 100KB of text).
 *
 * User-facing strings (error messages) are intentionally kept in French
 * because the target audience is French-speaking (SPIP CMS users).
 */
#[Layout('components.layouts.app')]
class MarkdownToSpipPage extends Component
{
    public const MAX_LENGTH = 100000; // 100KB

    public const MAX_ATTEMPTS = 200; // Requests per minute

    public string $markdown = '';

    public string $spip = '';

    /**
     * Triggered automatically on every Markdown text change.
     *
     * Checks text size and rate limit before performing the conversion.
     */
    public function updatedMarkdown(): void
    {
        // Stats: count the session once per session
        if (! session()->has('stats_counted')) {
            Stats::increment('sessions');
            session()->put('stats_counted', true);
        }

        // Size validation
        if (mb_strlen($this->markdown) > self::MAX_LENGTH) {
            $this->spip = __('messages.errors.too_long', [
                'max' => (string) Number::format(self::MAX_LENGTH),
            ]);

            return;
        }

        if (! $this->allow('markdown-convert', self::MAX_ATTEMPTS)) {
            $this->spip = __('messages.errors.rate_limit', ['max' => self::MAX_ATTEMPTS]);

            return;
        }

        $this->spip = MarkdownToSpipConverter::convert($this->markdown);
    }

    /**
     * Called when the user clicks the Copy button.
     * Records copy stats.
     */
    public function countCopy(): void
    {
        if ($this->markdown === '' || $this->spip === '') {
            return;
        }

        if (! $this->allow('count-copy', 60)) {
            return;
        }

        Stats::increment('copies');
        Stats::increment('total_chars', mb_strlen($this->markdown));
    }

    /**
     * Called once from the client side (via localStorage) on the first keystroke.
     */
    public function trackConversion(): void
    {
        if ($this->markdown === '') {
            return;
        }

        if (! $this->allow('track-conv', 20)) {
            return;
        }

        Stats::increment('conversions');
    }

    /**
     * Record one attempt for the caller's IP and tell whether it stays under the
     * given per-minute quota.
     */
    private function allow(string $action, int $maxAttempts): bool
    {
        $key = $action.':'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return false;
        }

        RateLimiter::hit($key, 60);

        return true;
    }

    /**
     * Render the Livewire component view.
     *
     * @return View Livewire view of the conversion page
     */
    public function render(): View
    {
        return view('livewire.markdown-to-spip-page');
    }
}
