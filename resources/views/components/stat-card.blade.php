@props(['label' => '', 'value' => 0, 'icon' => 'circle'])

<div class="p-5 bg-white dark:bg-gray-900 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 flex items-center space-x-4 transition hover:scale-[1.02] hover:shadow-lg duration-300">
    <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-800">
        @switch($icon)
            @case('students')
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-200" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2 20v-1a4 4 0 014-4h12a4 4 0 014 4v1" /></svg>
                @break

            @case('teachers')
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 14c2.5 0 4.5-2 4.5-4.5S14.5 5 12 5 7.5 7 7.5 9.5 9.5 14 12 14zM4 20v-1a4 4 0 014-4h8a4 4 0 014 4v1" /></svg>
                @break

            @case('subjects')
                <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v12m6-6H6" /></svg>
                @break

            @case('groups')
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM2 20v-2a4 4 0 014-4h2m8 0h2a4 4 0 014 4v2" /></svg>
                @break

            @case('grades')
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3l9.5 4.5-9.5 4.5L2.5 7.5 12 3zM12 21V12.75M9 21h6" /></svg>
                @break

            @case('average')
                <svg class="w-6 h-6 text-pink-600 dark:text-pink-300" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16l4-4 4 4 8-8M20 20H4" /></svg>
                @break

            @case('attendance')
                <svg class="w-6 h-6 text-rose-600 dark:text-rose-300" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h6m-9 7h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z" /></svg>
                @break

            @default
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-200" fill="currentColor"
                    viewBox="0 0 20 20"><circle cx="10" cy="10" r="8" /></svg>
        @endswitch
    </div>

    <div>
        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $value }}</div>
    </div>
</div>
