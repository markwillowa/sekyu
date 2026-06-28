<?php

namespace App\Http\Controllers\Agency\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AgencyRegisterController extends Controller
{
    public function create()
    {
        return view('agency.auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agency_name' => ['required', 'string', 'max:255'],
            'license_number' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['contact_person'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole('agency');

            Agency::create([
                'owner_id' => $user->id,
                'name' => $validated['agency_name'],
                'slug' => Str::slug($validated['agency_name']) . '-' . Str::lower(Str::random(6)),
                'license_number' => $validated['license_number'] ?? null,
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'city' => $validated['city'] ?? null,
                'province' => $validated['province'] ?? null,
                'country' => 'Philippines',
                'is_verified' => false,
                'is_active' => true,
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect()->route('agency.dashboard');
    }
}
