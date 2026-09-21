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

    public const STORAGE_RETENTION_DAYS = 7; // Draft kept in the browser after the last edit

    public string $markdown = '';

    public string $spip = '';

    /**
     * Triggered automatically on every Markdown text change.
     *
     * Checks text size and rate limit before performing the conversion.
     */
    public function updatedMarkdown(): void
    {
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

        $this->countSession();

        $this->spip = MarkdownToSpipConverter::convert($this->markdown);

        $this->rememberConversion();
    }

    /**
     * Called when the user clicks the Copy button.
     * Records one copy per conversion, for the length the server converted.
     */
    public function countCopy(): void
    {
        $chars = session()->get('pending_copy_chars');

        if (! is_int($chars)) {
            return;
        }

        if (! $this->allow('count-copy', 60)) {
            return;
        }

        session()->forget('pending_copy_chars');

        Stats::increment('copies');
        Stats::increment('total_chars', $chars);
    }

    /**
     * Called once from the client side (via localStorage) on the first keystroke.
     * Only a conversion the server actually performed can be counted.
     */
    public function trackConversion(): void
    {
        if (! session()->has('pending_conversion')) {
            return;
        }

        if (! $this->allow('track-conv', 20)) {
            return;
        }

        session()->forget('pending_conversion');

        Stats::increment('conversions');
    }

    /**
     * Count the session once, after the size and rate-limit gates.
     */
    private function countSession(): void
    {
        if (session()->has('stats_counted')) {
            return;
        }

        Stats::increment('sessions');
        session()->put('stats_counted', true);
    }

    /**
     * Keep what the server converted, so the stats actions account for real
     * conversions rather than for values the client sends.
     */
    private function rememberConversion(): void
    {
        if ($this->markdown === '') {
            session()->forget(['pending_copy_chars', 'pending_conversion']);

            return;
        }

        session()->put('pending_copy_chars', mb_strlen($this->markdown));
        session()->put('pending_conversion', true);
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
