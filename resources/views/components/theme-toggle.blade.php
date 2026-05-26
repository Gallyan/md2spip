@props(['offset' => 'dark:focus:ring-offset-slate-800'])
<button
    @click="toggleTheme()"
    {{ $attributes->merge(['class' => 'cursor-pointer w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 '.$offset]) }}
    :title="darkMode ? @js(__('messages.theme.switch_to_light_title')) : @js(__('messages.theme.switch_to_dark_title'))"
    :aria-label="darkMode ? @js(__('messages.theme.switch_to_light_aria')) : @js(__('messages.theme.switch_to_dark_aria'))"
>
    <x-icon.sun x-show="darkMode" style="display: none;" />
    <x-icon.moon x-show="!darkMode" style="display: none;" />
</button>
