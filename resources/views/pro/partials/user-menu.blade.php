@props([
    'mobile' => false,
])

<div
    x-data="{ open: false }"
    class="relative"
>

    <button
        type="button"
        @click="open = ! open"
        class="{{ $mobile ? 'h-10 w-10' : 'px-3 py-2' }} flex items-center justify-center rounded-xl transition hover:bg-slate-100"
    >

        <div
            class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 font-bold text-white"
        >

            {{ strtoupper(substr(auth('pro_agency')->user()->name, 0, 1)) }}

        </div>

        @unless($mobile)

            <div class="hidden pl-3 text-left lg:block">

                <div class="text-sm font-semibold text-slate-900">

                    {{ auth('pro_agency')->user()->name }}

                </div>

                <div class="text-xs text-slate-500">

                    {{ auth('pro_agency')->user()->role }}

                </div>

            </div>

        @endunless

    </button>

    <div
        x-show="open"
        @click.outside="open = false"
        x-transition
        x-cloak
        class="absolute right-0 z-50 mt-3 w-64 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
    >

        <div class="border-b border-slate-100 p-5">

            <div class="font-semibold text-slate-900">

                {{ auth('pro_agency')->user()->name }}

            </div>

            <div class="mt-1 text-sm text-slate-500">

                {{ auth('pro_agency')->user()->role }}

            </div>

        </div>

        <a
            href="#"
            class="block px-5 py-3 transition hover:bg-slate-50"
        >

            My Profile

        </a>

        <a
            href="#"
            class="block px-5 py-3 transition hover:bg-slate-50"
        >

            Settings

        </a>

        <form
            action="{{ route('pro.logout') }}"
            method="POST"
        >

            @csrf

            <button
                type="submit"
                class="block w-full px-5 py-3 text-left transition hover:bg-red-50 hover:text-red-600"
            >

                Logout

            </button>

        </form>

    </div>

</div>
