<?php

namespace App\Http\Controllers;

use App\Models\PickupPoint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PickupPointController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:is-admin');
    }

    public function index()
    {
        $pickupPoints = PickupPoint::with('manager')->get();
        return view('adminPickupPointsIndex', compact('pickupPoints'));
    }

    public function create()
    {
        return view('adminPickupPointsCreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'hours' => 'required|string|max:255',
            'manager_first_name' => 'required|string|max:255',
            'manager_last_name' => 'required|string|max:255',
            'manager_email' => 'required|email|unique:users,email',
            'manager_password' => 'required|confirmed|min:8',
        ]);

        $manager = User::create([
            'first_name' => $validated['manager_first_name'],
            'last_name' => $validated['manager_last_name'],
            'email' => $validated['manager_email'],
            'password' => Hash::make($validated['manager_password']),
            'role' => 'manager',
        ]);

        PickupPoint::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'hours' => $validated['hours'],
            'manager_id' => $manager->id,
        ]);

        return redirect()->route('admin.pickup_points.index')->with('success', 'Пункт выдачи успешно создан.');
    }

    public function edit(PickupPoint $pickupPoint)
    {
        return view('adminPickupPointsEdit', compact('pickupPoint'));
    }

    public function update(Request $request, PickupPoint $pickupPoint)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'hours' => 'required|string|max:255',
        ]);

        $pickupPoint->update($validated);
        return redirect()->route('admin.pickup_points.index')->with('success', 'Пункт выдачи успешно обновлен.');
    }

    public function destroy(PickupPoint $pickupPoint)
    {
        if ($pickupPoint->manager) {
            $pickupPoint->manager->delete();
        }
        $pickupPoint->delete();
        return redirect()->route('admin.pickup_points.index')->with('success', 'Пункт выдачи успешно удален.');
    }
}