<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Livewire\MarkdownToSpipPage;
use App\Support\MarkdownToSpipConverter;
use App\Support\Stats;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Number;

/**
 * Converts the raw request body from Markdown to SPIP, for scripts and agents.
 *
 * The body is plain text, as is the answer. Same size limit as the converter
 * page; conversions are counted apart from the page ones in the public stats.
 */
final class ConvertController extends Controller
{
    public const MAX_ATTEMPTS = 60;

    public function __invoke(Request $request): Response
    {
        $key = 'api-convert:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            return $this->plain(
                __('messages.errors.rate_limit', ['max' => self::MAX_ATTEMPTS]),
                429,
                ['Retry-After' => (string) RateLimiter::availableIn($key)],
            );
        }

        RateLimiter::hit($key, 60);

        $markdown = $request->getContent();

        if (! mb_check_encoding($markdown, 'UTF-8')) {
            return $this->plain(__('messages.errors.invalid_utf8'), 400);
        }

        if (mb_strlen($markdown) > MarkdownToSpipPage::MAX_LENGTH) {
            return $this->plain(__('messages.errors.too_long', [
                'max' => (string) Number::format(MarkdownToSpipPage::MAX_LENGTH),
            ]), 413);
        }

        if (trim($markdown) !== '') {
            Stats::increment('api_conversions');
        }

        return $this->plain(MarkdownToSpipConverter::convert($markdown));
    }

    /**
     * @param  array<string, string>  $headers
     */
    private function plain(string $body, int $status = 200, array $headers = []): Response
    {
        return response($body, $status, ['Content-Type' => 'text/plain; charset=UTF-8', ...$headers]);
    }
}
