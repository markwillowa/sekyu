<x-framework.feedback.modal
    name="login-modal"
    :show="session('open_login_modal') || in_array(old('account_type'), ['agency', 'applicant', 'guard'], true)"
    maxWidth="lg"
    title="Welcome Back"
    description="Sign in to continue to your dashboard"
>
    <div x-data="{ accountType: '{{ old('account_type') === 'applicant' ? 'guard' : old('account_type', 'guard') }}' }">
        <!-- Switcher -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <button
                type="button"
                @click="accountType = 'guard'"
                :class="accountType === 'guard' ? 'border-amber-500 ring-2 ring-amber-500 ring-opacity-50 bg-white shadow-sm' : 'border-slate-200 bg-slate-50 opacity-70'"
                class="relative flex flex-col items-start p-4 rounded-2xl border-2 transition-all duration-200 group text-left"
            >
                <span
                    :class="accountType === 'guard' ? 'text-amber-500 bg-amber-50' : 'text-slate-400 bg-slate-100'"
                    class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 transition-colors group-hover:bg-amber-50"
                >
                    🛡️
                </span>
                <span :class="accountType === 'guard' ? 'text-slate-900 font-bold' : 'text-slate-600 font-semibold'" class="block text-sm">Find a Job</span>
                <span class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Guard</span>
            </button>

            <button
                type="button"
                @click="accountType = 'agency'"
                :class="accountType === 'agency' ? 'border-amber-500 ring-2 ring-amber-500 ring-opacity-50 bg-white shadow-sm' : 'border-slate-200 bg-slate-50 opacity-70'"
                class="relative flex flex-col items-start p-4 rounded-2xl border-2 transition-all duration-200 group text-left"
            >
                <span
                    :class="accountType === 'agency' ? 'text-amber-500 bg-amber-50' : 'text-slate-400 bg-slate-100'"
                    class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 transition-colors group-hover:bg-amber-50"
                >
                    🏢
                </span>
                <span :class="accountType === 'agency' ? 'text-slate-900 font-bold' : 'text-slate-600 font-semibold'" class="block text-sm">Hire Guards</span>
                <span class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Employer</span>
            </button>
        </div>

        <!-- Guard Login Form -->
        <div x-show="accountType === 'guard'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0">
            <form method="POST" action="{{ route('applicant.login.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="account_type" value="guard">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input
                        name="email"
                        type="email"
                        required
                        placeholder="Enter your email"
                        class="w-full rounded-xl border-slate-200 px-4 py-2.5 focus:border-amber-500 focus:ring-amber-500 transition-all bg-slate-50 focus:bg-white text-sm"
                        value="{{ old('email') && in_array(old('account_type'), ['applicant', 'guard'], true) ? old('email') : '' }}"
                    >
                    @error('email')
                        @if(in_array(old('account_type'), ['applicant', 'guard'], true) || !old('account_type'))
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @endif
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Password</label>
                        <a href="#" class="text-xs font-semibold text-amber-600 hover:text-amber-700">Forgot?</a>
                    </div>
                    <input
                        name="password"
                        type="password"
                        required
                        placeholder="••••••••"
                        class="w-full rounded-xl border-slate-200 px-4 py-2.5 focus:border-amber-500 focus:ring-amber-500 transition-all bg-slate-50 focus:bg-white text-sm"
                    >
                </div>

                <div class="flex items-center">
                    <label class="flex items-center gap-2.5 text-sm text-slate-600 cursor-pointer group">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500 transition-all cursor-pointer"
                        >
                        <span class="group-hover:text-slate-900 transition-colors text-xs">Remember me</span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl hover:bg-slate-800 transition-all transform active:scale-[0.98] shadow-lg shadow-slate-200"
                >
                    Sign In
                </button>

                <p class="text-center text-xs text-slate-500 mt-4">
                    Don't have an account?
                    <a href="{{ route('applicant.register') }}" class="font-bold text-amber-600 hover:text-amber-700">Create Guard Account</a>
                </p>
            </form>
        </div>

        <!-- Agency Login Form -->
        <div x-show="accountType === 'agency'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             style="display: none;">
            <form method="POST" action="{{ route('agency.login.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="account_type" value="agency">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Company Email</label>
                    <input
                        name="email"
                        type="email"
                        required
                        placeholder="agency@example.com"
                        class="w-full rounded-xl border-slate-200 px-4 py-2.5 focus:border-amber-500 focus:ring-amber-500 transition-all bg-slate-50 focus:bg-white text-sm"
                        value="{{ old('email') && old('account_type') === 'agency' ? old('email') : '' }}"
                    >
                    @error('email')
                        @if(old('account_type') === 'agency')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @endif
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-semibold text-slate-700">Password</label>
                        <a href="#" class="text-xs font-semibold text-amber-600 hover:text-amber-700">Forgot?</a>
                    </div>
                    <input
                        name="password"
                        type="password"
                        required
                        placeholder="••••••••"
                        class="w-full rounded-xl border-slate-200 px-4 py-2.5 focus:border-amber-500 focus:ring-amber-500 transition-all bg-slate-50 focus:bg-white text-sm"
                    >
                </div>

                <div class="flex items-center">
                    <label class="flex items-center gap-2.5 text-sm text-slate-600 cursor-pointer group">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500 transition-all cursor-pointer"
                        >
                        <span class="group-hover:text-slate-900 transition-colors text-xs">Remember me</span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl hover:bg-slate-800 transition-all transform active:scale-[0.98] shadow-lg shadow-slate-200"
                >
                    Sign In
                </button>

                <p class="text-center text-xs text-slate-500 mt-4">
                    Need an employer account?
                    <a href="{{ route('agency.register') }}" class="font-bold text-amber-600 hover:text-amber-700">Register Agency</a>
                </p>
            </form>
        </div>
    </div>
</x-framework.feedback.modal>
