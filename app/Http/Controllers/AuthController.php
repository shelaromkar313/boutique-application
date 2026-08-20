<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show multi-role login page.
     */
    public function showLogin(Request $request)
    {
        $role = $request->query('role', 'customer');
        return view('login', compact('role'));
    }

    /**
     * Handle multi-role authentication (Email or Phone + Password/OTP).
     */
    public function login(Request $request)
    {
        $role     = $request->input('role', 'customer');
        $authType = $request->input('auth_type', 'email');
        $email    = trim($request->input('email', ''));
        $phone    = trim($request->input('phone', ''));
        $password = $request->input('password', '');
        $otp      = $request->input('otp', '');

        // ── 1. Phone + OTP Login Flow ────────────────────────────────────────
        if ($authType === 'phone_otp') {
            if (empty($phone)) {
                return back()->withErrors(['phone' => 'Please enter a valid 10-digit mobile number.'])->withInput();
            }

            // Validate OTP against DB (falls back to demo code '1234')
            $otpRecord = \DB::table('otp_codes')
                ->where('phone', $phone)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

            $validOtp = ($otpRecord && $otpRecord->code === $otp) || $otp === '1234';

            if (!$validOtp) {
                return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.'])->withInput();
            }

            // Mark OTP as used
            if ($otpRecord) {
                \DB::table('otp_codes')->where('id', $otpRecord->id)->update(['is_used' => true]);
            }

            $user = User::where('phone', $phone)->first();
            if (!$user) {
                if ($role === 'sales_associate') {
                    return back()->withErrors(['phone' => 'No sales partner found with this number. Please register.'])->withInput();
                }
                if ($role === 'admin') {
                    return back()->withErrors(['phone' => 'Admin phone login requires a pre-registered administrator number.'])->withInput();
                }
                // Auto-create customer on first phone login
                $user = User::create([
                    'name'     => 'Guest Shopper (' . substr($phone, -4) . ')',
                    'phone'    => $phone,
                    'email'    => 'phone_' . $phone . '@estilowear.in',
                    'password' => Hash::make(Str::random(16)),
                    'role'     => 'customer',
                ]);
            }

            Auth::login($user, true);
            $this->logLogin($user, 'phone_otp', $request);
            return $this->redirectBasedOnRole($user);
        }

        // ── 2. Email + Password Login Flow ───────────────────────────────────
        $user = !empty($email)
            ? User::where('email', $email)->first()
            : User::where('phone', $phone)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            // Demo shortcut — create users if missing (dev convenience)
            if ($email === 'admin@estilo.com' && $password === 'Admin@123') {
                $user = User::firstOrCreate(['email' => 'admin@estilo.com'], [
                    'name' => 'Boutique Admin', 'role' => 'admin',
                    'password' => Hash::make('Admin@123'), 'phone' => '9000000001',
                ]);
            } elseif ($email === 'admin@estilo.com' && $password === 'password123') {
                // Legacy demo password support
                $user = User::where('email', 'admin@estilo.com')->first();
                if ($user) { $user->update(['password' => Hash::make('Admin@123')]); }
            } elseif ($email === 'associate@estilo.com' && in_array($password, ['Partner@123', 'password123'])) {
                $user = User::firstOrCreate(['email' => 'associate@estilo.com'], [
                    'name' => 'Pooja Verma', 'role' => 'sales_associate',
                    'referral_code' => 'ESTILO-SA01',
                    'password' => Hash::make('Partner@123'), 'phone' => '9876543211',
                ]);
            } elseif ($email === 'test@example.com' && in_array($password, ['Customer@123', 'password123'])) {
                $user = User::firstOrCreate(['email' => 'test@example.com'], [
                    'name' => 'Test Customer', 'role' => 'customer',
                    'password' => Hash::make('Customer@123'), 'phone' => '9876543212',
                ]);
            } else {
                return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput();
            }
        }

        Auth::login($user, $request->has('remember'));
        $this->logLogin($user, 'email', $request);
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Customer registration.
     */
    public function registerCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);
        return redirect('/')->with('success', '✨ Welcome to Estilo Wear Couture, ' . $user->name . '!');
    }

    /**
     * Sales Associate registration.
     */
    public function registerSales(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'upi_id' => 'nullable|string|max:100',
            'password' => 'required|min:6',
        ]);

        $refCode = 'ESTILO-' . strtoupper(Str::random(4)) . rand(10, 99);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'upi_id' => $request->upi_id,
            'password' => Hash::make($request->password),
            'role' => 'sales_associate',
            'referral_code' => $refCode,
            'commission_rate' => 10.00,
            'earnings' => 0.00,
            'balance' => 0.00,
        ]);

        Auth::login($user);
        return redirect('/sales/dashboard')->with('success', '🎉 Welcome to the Estilo Partner Program! Your referral code is ' . $refCode);
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'You have been logged out safely.');
    }

    /**
     * Redirect helper by user role.
     */
    protected function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard')->with('success', '👑 Welcome back, Administrator!');
        }

        if ($user->isSalesAssociate()) {
            return redirect('/sales/dashboard')->with('success', '💼 Welcome to your Sales Associate Atelier Portal!');
        }

        return redirect('/')->with('success', '✨ Welcome back, ' . $user->name . '!');
    }

    /**
     * Write a login audit record.
     */
    private function logLogin(User $user, string $method, Request $request): void
    {
        try {
            DB::table('user_logins')->insert([
                'user_id'     => $user->id,
                'role'        => $user->role,
                'auth_method' => $method,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'logged_in_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Non-critical — never block login because of audit failure
            logger()->warning('Login audit failed: ' . $e->getMessage());
        }
    }
}
