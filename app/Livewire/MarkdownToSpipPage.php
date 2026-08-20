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

        // Rate limiting: MAX_ATTEMPTS conversions per minute per IP (with 50ms front-end debounce)
        $key = 'markdown-convert:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $this->spip = __('messages.errors.rate_limit', ['max' => self::MAX_ATTEMPTS]);

            return;
        }

        // Increment RateLimiter counter (expires after 60 seconds)
        RateLimiter::hit($key, 60);

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

        $key = 'count-copy:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 60)) {
            return;
        }
        RateLimiter::hit($key, 60);

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

        $key = 'track-conv:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 20)) {
            return;
        }
        RateLimiter::hit($key, 60);

        Stats::increment('conversions');
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
