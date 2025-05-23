<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['register', 'store', 'login', 'authenticate']);
    }

    public function register()
    {
        return view('authRegister');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'middle_name' => $validated['middle_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('products.index')->with('success', 'Регистрация успешна.');
    }

    public function login()
    {
        return view('authLogin');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('products.index'))->with('success', 'Вход выполнен.');
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ])->onlyInput('email');
    }

    public function profile()
    {
        try {
            $user = Auth::user();
            $orders = Order::where('user_id', $user->id)
                ->with(['items.product', 'items.size', 'items.color'])
                ->orderBy('order_date', 'desc')
                ->get();

            Log::info('User profile loaded', [
                'user_id' => $user->id,
                'order_count' => $orders->count(),
            ]);

            return view('profileIndex', compact('user', 'orders'));
        } catch (\Exception $e) {
            Log::error('Error loading user profile: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return redirect()->route('products.index')->with('error', 'Не удалось загрузить профиль.');
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'delivery_address' => 'nullable|string|max:255',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            Log::info('Updating user profile', ['user_id' => $user->id, 'validated' => $validated]);

            $updateData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'],
                'email' => $validated['email'],
                'delivery_address' => $validated['delivery_address'],
            ];

            if ($validated['password']) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $updated = $user->update($updateData);

            if (!$updated) {
                Log::error('User profile update failed', ['user_id' => $user->id]);
                return redirect()->route('profile.index')->with('error', 'Не удалось обновить профиль. Попробуйте снова.');
            }

            Log::info('User profile updated successfully', ['user_id' => $user->id]);

            return redirect()->route('profile.index')->with('success', 'Профиль обновлен.');
        } catch (QueryException $e) {
            Log::error('Database error during profile update: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return redirect()->route('profile.index')->with('error', 'Ошибка базы данных при обновлении профиля.');
        } catch (\Exception $e) {
            Log::error('Unexpected error during profile update: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return redirect()->route('profile.index')->with('error', 'Не удалось обновить профиль. Попробуйте снова.');
        }
    }

    public function confirmOrder(Request $request, $cart)
    {
        $user = Auth::user();
        $order = Order::where('id', $cart)->where('user_id', $user->id)->firstOrFail();

        Log::info('Confirming order', ['order_id' => $order->id, 'user_id' => $user->id]);

        $order->update(['status' => 'completed']);

        Log::info('Order confirmed', ['order_id' => $order->id]);

        return redirect()->route('profile.index')->with('success', 'Заказ подтвержден.');
    }
}