@props([
    'name',
])

@error($name)
<p class="mt-2 flex items-center gap-2 text-sm font-medium text-red-600">
    <svg xmlns="http://www.w3.org/2000/svg"
         class="h-4 w-4"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor">
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 9v2m0 4h.01M12 3C7.03 3 3 7.03 3 12s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9z"
        />
    </svg>

    {{ $message }}
</p>
@enderror
