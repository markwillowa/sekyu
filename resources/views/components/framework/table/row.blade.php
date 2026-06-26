<tr
    {{ $attributes->merge([
        'class' => 'transition hover:bg-slate-50',
    ]) }}
>
    {{ $slot }}
</tr>
