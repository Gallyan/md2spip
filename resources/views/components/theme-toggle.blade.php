@props(['offset' => 'dark:focus:ring-offset-slate-800'])
<button
    @click="cycleTheme()"
    x-data="{ themeNames: { system: @js(__('messages.theme.system')), dark: @js(__('messages.theme.dark')), light: @js(__('messages.theme.light')) } }"
    {{ $attributes->merge(['class' => 'cursor-pointer w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 '.$offset]) }}
    :title="@js(__('messages.theme.title_prefix')) + ' ' + themeNames[theme]"
    :aria-label="@js(__('messages.theme.cycle_aria')) + ' — ' + themeNames[theme]"
>
    <x-icon.computer-desktop x-show="theme === 'system'" style="display: none;" />
    <x-icon.moon x-show="theme === 'dark'" style="display: none;" />
    <x-icon.sun x-show="theme === 'light'" style="display: none;" />
</button>
