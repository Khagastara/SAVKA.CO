<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AccountController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function showProfile()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the authenticated user's own profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|unique:users,phone_number,' . $user->id,
            'address' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Display all staff (only for Owner).
     */
    public function showStaff()
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            abort(403, 'Access denied.');
        }

        $staff = User::whereIn('role', ['Production Staff', 'Distribution Staff'])->get();

        return view('owner.staff.index', compact('staff'));
    }

    /**
     * Create a new staff account (only for Owner).
     */
    public function createStaff(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:users',
            'address' => 'required|string|max:255',
            'role' => 'required|in:Production Staff,Distribution Staff',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $staff = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Staff created successfully!',
            'data' => $staff
        ]);
    }

    /**
     * Update staff account (only for Owner).
     */
    public function updateStaff(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            abort(403, 'Access denied.');
        }

        $staff = User::findOrFail($id);

        if (!in_array($staff->role, ['Production Staff', 'Distribution Staff'])) {
            abort(403, 'User is not a staff member.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'phone_number' => 'required|string|unique:users,phone_number,' . $staff->id,
            'address' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $staff->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Staff updated successfully!',
            'data' => $staff
        ]);
    }
}
