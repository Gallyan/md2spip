<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Markdown → SPIP correspondences shown to users, in the help modal
 * (essentials) and on the guide page (all).
 */
final class SyntaxReference
{
    /**
     * @return list<array{
     *     md: string,
     *     spip: string
     * }>
     */
    public static function essentials(): array
    {
        $w = self::words();

        return [
            ['md' => "# {$w['title']}", 'spip' => "{{{{$w['title']}}}}"],
            ['md' => "## {$w['subtitle']}", 'spip' => "{{{$w['subtitle']}}}"],
            ['md' => "**{$w['bold']}**", 'spip' => "{{{$w['bold']}}}"],
            ['md' => "*{$w['italic']}*", 'spip' => "{{$w['italic']}}"],
            ['md' => "[{$w['link']}](url)", 'spip' => "[{$w['link']}->url]"],
            ['md' => "- {$w['item']}", 'spip' => "-* {$w['item']}"],
            ['md' => "1. {$w['item']}", 'spip' => "-# {$w['item']}"],
            ['md' => '---', 'spip' => '----'],
            ['md' => "`{$w['code']}`", 'spip' => "<code>{$w['code']}</code>"],
            ['md' => "> {$w['quote']}", 'spip' => "<quote>{$w['quote']}</quote>"],
            ['md' => "{$w['text']}[^1]", 'spip' => "{$w['text']}[[{$w['note']}]]"],
        ];
    }

    /**
     * @return list<array{
     *     md: string,
     *     spip: string
     * }>
     */
    public static function all(): array
    {
        $w = self::words();

        return [
            ...self::essentials(),
            ['md' => "***{$w['bold']}***", 'spip' => "{{ { {$w['bold']} } }}"],
            ['md' => "~~{$w['strike']}~~", 'spip' => "<del>{$w['strike']}</del>"],
            ['md' => "  - {$w['item']}", 'spip' => "-** {$w['item']}"],
            ['md' => '<https://…>', 'spip' => '[->https://…]'],
            ['md' => '![alt](url)', 'spip' => '[alt->url]'],
            ['md' => "| a | b |\n|---|---|\n| 1 | 2 |", 'spip' => "| {{a}} | {{b}} |\n| 1 | 2 |"],
        ];
    }

    /** @return array<string, string> */
    private static function words(): array
    {
        /** @var array<string, string> $words */
        $words = trans('messages.help.words');

        return $words;
    }
}
