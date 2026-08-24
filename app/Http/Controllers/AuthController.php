<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

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
     * Handle multi-role authentication (Web form or JSON API).
     */
    public function login(Request $request)
    {
        // If request is from API / Expects JSON, route to apiLogin
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->apiLogin($request);
        }

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
                $user = User::create([
                    'name'     => 'Guest Shopper (' . substr($phone, -4) . ')',
                    'phone'    => $phone,
                    'email'    => 'phone_' . $phone . '@estilowear.in',
                    'password' => Hash::make(Str::random(16)),
                    'role'     => 'customer',
                ]);
            }

            Auth::login($user, true);
            $token = Auth::guard('api')->login($user);
            session(['jwt_token' => $token]);
            $this->logLogin($user, 'phone_otp', $request);
            return $this->redirectBasedOnRole($user);
        }

        // ── 2. Email + Password Login Flow ───────────────────────────────────
        $user = !empty($email)
            ? User::where('email', $email)->first()
            : User::where('phone', $phone)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            // Demo shortcut
            if ($email === 'admin@estilo.com' && in_array($password, ['Admin@123', 'password123'])) {
                $user = User::firstOrCreate(['email' => 'admin@estilo.com'], [
                    'name' => 'Boutique Admin', 'role' => 'admin',
                    'password' => Hash::make('Admin@123'), 'phone' => '9000000001',
                ]);
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
        $token = Auth::guard('api')->login($user);
        session(['jwt_token' => $token]);

        $this->logLogin($user, 'email', $request);
        return $this->redirectBasedOnRole($user);
    }

    /**
     * API Login Endpoint (Returns JWT Bearer Token).
     */
    public function apiLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required_without:phone|email',
            'phone'    => 'required_without:email|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');
        if (empty($credentials['email']) && $request->filled('phone')) {
            $userByPhone = User::where('phone', $request->phone)->first();
            if ($userByPhone) {
                $credentials = ['email' => $userByPhone->email, 'password' => $request->password];
            }
        }

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            // Seed check for demo admin/partner
            if ($request->email === 'admin@estilo.com' && $request->password === 'Admin@123') {
                $admin = User::firstOrCreate(['email' => 'admin@estilo.com'], [
                    'name' => 'Boutique Admin', 'role' => 'admin',
                    'password' => Hash::make('Admin@123'), 'phone' => '9000000001',
                ]);
                $token = Auth::guard('api')->login($admin);
                return $this->respondWithToken($token, $admin);
            }

            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::guard('api')->user();
        $this->logLogin($user, 'api_jwt', $request);

        return $this->respondWithToken($token, $user);
    }

    /**
     * API Register Endpoint (Returns JWT Token).
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'role'     => 'nullable|string|in:customer,sales_associate',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = $request->input('role', 'customer');
        $userData = [
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => $role,
        ];

        if ($role === 'sales_associate') {
            $userData['referral_code'] = 'ESTILO-' . strtoupper(Str::random(4)) . rand(10, 99);
            $userData['commission_rate'] = 10.00;
            $userData['earnings'] = 0.00;
            $userData['balance'] = 0.00;
        }

        $user = User::create($userData);
        $token = Auth::guard('api')->login($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $user,
            'token'   => $this->respondWithToken($token, $user)->original,
        ], 201);
    }

    /**
     * Get the authenticated User profile via JWT.
     */
    public function me()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        return response()->json([
            'user' => $user,
            'is_admin' => $user->isAdmin(),
            'is_sales' => $user->isSalesAssociate(),
        ]);
    }

    /**
     * Refresh a JWT token.
     */
    public function refresh()
    {
        try {
            $token = Auth::guard('api')->refresh();
            return $this->respondWithToken($token);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Token refresh failed: ' . $e->getMessage()], 401);
        }
    }

    /**
     * API Logout.
     */
    public function apiLogout()
    {
        try {
            Auth::guard('api')->logout();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Session terminated']);
        }
    }

    /**
     * Customer registration for Web UI.
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
        $token = Auth::guard('api')->login($user);
        session(['jwt_token' => $token]);

        return redirect('/')->with('success', '✨ Welcome to Estilo Wear Couture, ' . $user->name . '!');
    }

    /**
     * Sales Associate registration for Web UI.
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
        $token = Auth::guard('api')->login($user);
        session(['jwt_token' => $token]);

        return redirect('/sales/dashboard')->with('success', '🎉 Welcome to the Estilo Partner Program! Your referral code is ' . $refCode);
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        try {
            Auth::guard('api')->logout();
        } catch (\Throwable $e) {}

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
            return redirect('/admin')->with('success', '👑 Welcome back, Administrator!');
        }

        if ($user->isSalesAssociate()) {
            return redirect('/sales/dashboard')->with('success', '💼 Welcome to your Sales Associate Atelier Portal!');
        }

        return redirect('/')->with('success', '✨ Welcome back, ' . $user->name . '!');
    }

    /**
     * Format JWT token response structure.
     */
    protected function respondWithToken($token, ?User $user = null)
    {
        $user = $user ?? Auth::guard('api')->user();
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60,
            'user'         => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'role'     => $user->role,
                'phone'    => $user->phone,
            ]
        ]);
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
            logger()->warning('Login audit failed: ' . $e->getMessage());
        }
    }
}

