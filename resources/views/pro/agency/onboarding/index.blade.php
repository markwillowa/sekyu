@extends('pro.layouts.app')

@section('title', 'Employee Onboarding - SEKYU PRO')

@section('content')

    <x-framework.layout.page-header
        title="Employee Onboarding"
        description="Add employees, issue portal access and keep workforce records ready for operations."
    >
        <x-slot:actions>
            <x-framework.buttons.primary href="{{ route('pro.agency.onboarding.create') }}">
                Onboard Employee
            </x-framework.buttons.primary>
        </x-slot:actions>
    </x-framework.layout.page-header>

    <div class="space-y-6">

        @if(session('status'))
            <x-framework.feedback.alert type="success">
                <div class="font-semibold">
                    {{ session('status') }}
                </div>

                @if(session('temporary_password'))
                    <div class="mt-2">
                        Username:
                        <span class="font-mono font-semibold">{{ session('username') }}</span>
                    </div>

                    <div class="mt-1">
                        Temporary PIN:
                        <span class="font-mono font-semibold">{{ session('temporary_password') }}</span>
                    </div>
                @endif
            </x-framework.feedback.alert>
        @endif

        <x-framework.layout.card>
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <div class="text-sm font-medium text-slate-500">Total Employees</div>
                    <div class="mt-2 text-3xl font-bold text-slate-900">{{ $employees->total() }}</div>
                </div>

                <div>
                    <div class="text-sm font-medium text-slate-500">Active Accounts</div>
                    <div class="mt-2 text-3xl font-bold text-slate-900">
                        {{ $activeAccountCount }}
                    </div>
                </div>

                <div>
                    <div class="text-sm font-medium text-slate-500">Latest Hire</div>
                    <div class="mt-2 text-lg font-semibold text-slate-900">
                        {{ optional($employees->first()?->date_hired)->format('M d, Y') ?? 'None yet' }}
                    </div>
                </div>
            </div>
        </x-framework.layout.card>

        @if($employees->isEmpty())
            <x-framework.layout.card>
                <div class="py-16 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                        <x-framework.icon
                            name="user-plus"
                            class="h-8 w-8 text-slate-400"
                        />
                    </div>

                    <h2 class="mt-6 text-lg font-semibold text-slate-900">
                        No employees onboarded yet
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Start with a basic employee profile and optional portal account.
                    </p>

                    <div class="mt-6">
                        <x-framework.buttons.primary href="{{ route('pro.agency.onboarding.create') }}">
                            Start Onboarding
                        </x-framework.buttons.primary>
                    </div>
                </div>
            </x-framework.layout.card>
        @else
            <x-framework.table.table>
                <x-framework.table.head>
                    <x-framework.table.row>
                        <x-framework.table.th>Employee</x-framework.table.th>
                        <x-framework.table.th>Employment</x-framework.table.th>
                        <x-framework.table.th>Assignment</x-framework.table.th>
                        <x-framework.table.th>Portal Access</x-framework.table.th>
                        <x-framework.table.th>Date Hired</x-framework.table.th>
                        <x-framework.table.th class="text-right">Actions</x-framework.table.th>
                    </x-framework.table.row>
                </x-framework.table.head>

                <x-framework.table.body>
                    @foreach($employees as $employee)
                        <x-framework.table.row>
                            <x-framework.table.td>
                                <div class="font-semibold text-slate-900">{{ $employee->full_name }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $employee->employee_no }}</div>
                            </x-framework.table.td>

                            <x-framework.table.td>
                                <div class="font-medium text-slate-900">{{ $employee->position }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $employee->department }}</div>
                            </x-framework.table.td>

                            <x-framework.table.td>
                                <div>{{ $employee->current_site ?: 'Unassigned' }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $employee->current_shift ?: 'No shift yet' }}</div>
                            </x-framework.table.td>

                            <x-framework.table.td>
                                @if($employee->account)
                                    <x-framework.feedback.badge color="green">Enabled</x-framework.feedback.badge>
                                @else
                                    <x-framework.feedback.badge>No account</x-framework.feedback.badge>
                                @endif
                            </x-framework.table.td>

                            <x-framework.table.td>
                                {{ $employee->date_hired->format('M d, Y') }}
                            </x-framework.table.td>

                            <x-framework.table.td>
                                <x-framework.table.actions>
                                    @if($employee->account)
                                        <form
                                            method="POST"
                                            action="{{ route('pro.agency.onboarding.reset-pin', $employee) }}"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-100"
                                            >
                                                Reset PIN
                                            </button>
                                        </form>
                                    @endif

                                    <x-framework.buttons.secondary
                                        href="{{ route('pro.agency.onboarding.edit', $employee) }}"
                                        class="px-4 py-2"
                                    >
                                        Edit
                                    </x-framework.buttons.secondary>
                                </x-framework.table.actions>
                            </x-framework.table.td>
                        </x-framework.table.row>
                    @endforeach
                </x-framework.table.body>
            </x-framework.table.table>

            {{ $employees->links() }}
        @endif
    </div>

@endsection
