<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LabelSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Բարի գալուստ համակարգ!');
        }

        return back()->withErrors([
            'email' => 'Մուտքանունը կամ գաղտնաբառը սխալ է:',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create default XP-356B 20x30mm label setting for the new user
        LabelSetting::create([
            'user_id' => $user->id,
            'setting_name' => 'XP-356B (20x30մմ)',
            'width_mm' => 20.0,
            'height_mm' => 30.0,
            'margin_mm' => 1.0,
            'orientation' => 'portrait',
            'product_font_size' => 9,
            'product_font_bold' => true,
            'product_pos_x' => 1.5,
            'product_pos_y' => 2.0,
            'last5_font_size' => 11,
            'last5_font_bold' => true,
            'last5_pos_x' => 1.5,
            'last5_pos_y' => 7.0,
            'datamatrix_size' => 15.0,
            'datamatrix_pos_x' => 2.5,
            'datamatrix_pos_y' => 12.0,
            'is_default' => true,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Հաշիվը հաջողությամբ ստեղծվեց:');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Դուք դուրս եկաք համակարգից:');
    }
}
