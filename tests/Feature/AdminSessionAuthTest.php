<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSessionAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_api_login_authenticates_session(): void
    {
        $admin = User::create([
            'name' => 'Boutique Admin',
            'email' => 'admin@estilo.com',
            'phone' => '9000000001',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'admin@estilo.com',
            'password' => 'Admin@123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged in successfully',
                'session_authenticated' => true,
                'user' => [
                    'id' => $admin->id,
                    'email' => 'admin@estilo.com',
                    'role' => 'admin',
                ],
            ]);

        $this->assertAuthenticatedAs($admin);
    }

    public function test_authenticated_admin_can_access_me_endpoint(): void
    {
        $admin = User::create([
            'name' => 'Boutique Admin',
            'email' => 'admin@estilo.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'id' => $admin->id,
                    'email' => 'admin@estilo.com',
                    'role' => 'admin',
                ],
                'is_admin' => true,
                'is_sales' => false,
            ]);
    }

    public function test_unauthenticated_user_cannot_access_admin_stats(): void
    {
        $response = $this->getJson('/api/admin/dashboard-stats');
        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_access_admin_stats(): void
    {
        $customer = User::create([
            'name' => 'Regular Customer',
            'email' => 'customer@estilo.com',
            'password' => Hash::make('Customer@123'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->getJson('/api/admin/dashboard-stats');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard_stats(): void
    {
        $admin = User::create([
            'name' => 'Boutique Admin',
            'email' => 'admin@estilo.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->getJson('/api/admin/dashboard-stats');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'metrics' => [
                    'gross_revenue',
                    'total_orders',
                    'total_products',
                    'in_stock_products',
                    'total_customers',
                    'total_associates',
                    'total_commission_paid',
                ],
            ]);
    }

    public function test_admin_dashboard_uses_real_zero_counts_when_database_is_empty(): void
    {
        $admin = User::create([
            'name' => 'Boutique Admin',
            'email' => 'admin@estilo.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->getJson('/api/admin/dashboard-stats');

        $response->assertStatus(200)
            ->assertJsonPath('metrics.gross_revenue', 0)
            ->assertJsonPath('metrics.total_orders', 0)
            ->assertJsonPath('metrics.total_customers', 0)
            ->assertJsonPath('metrics.total_products', 0);
    }

    public function test_user_can_logout_and_session_is_cleared(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@estilo.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->postJson('/api/auth/logout');
        $response->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);

        $this->assertGuest();
    }

    public function test_customer_web_registration_authenticates_session_and_redirects(): void
    {
        $response = $this->post('/register', [
            'name' => 'Aanya Sharma',
            'email' => 'aanya@example.com',
            'phone' => '9876500001',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $user = User::where('email', 'aanya@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('customer', $user->role);
        $this->assertAuthenticatedAs($user);
    }

    public function test_sales_associate_web_registration_generates_ref_code_and_authenticates(): void
    {
        $response = $this->post('/sales/register', [
            'name' => 'Kavita Roy',
            'email' => 'kavita@example.com',
            'phone' => '9876500002',
            'upi_id' => 'kavita@upi',
            'password' => 'partnerPass123',
        ]);

        $response->assertRedirect('/sales/dashboard');
        $this->assertAuthenticated();

        $user = User::where('email', 'kavita@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('sales_associate', $user->role);
        $this->assertStringStartsWith('ESTILO-', $user->referral_code);
        $this->assertAuthenticatedAs($user);
    }

    public function test_web_login_with_valid_credentials_redirects_and_authenticates_session(): void
    {
        $user = User::create([
            'name' => 'Existing Customer',
            'email' => 'existing@estilo.com',
            'password' => Hash::make('mypassword'),
            'role' => 'customer',
        ]);

        $response = $this->post('/login', [
            'auth_type' => 'email',
            'email' => 'existing@estilo.com',
            'password' => 'mypassword',
        ]);

        $response->assertRedirect('/profile');
        $this->assertAuthenticatedAs($user);
    }

    public function test_phone_otp_login_authenticates_session(): void
    {
        $phone = '9998887776';
        DB::table('otp_codes')->insert([
            'phone' => $phone,
            'code' => '5678',
            'is_used' => false,
            'expires_at' => now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post('/login', [
            'auth_type' => 'phone_otp',
            'phone' => $phone,
            'otp' => '5678',
            'role' => 'customer',
        ]);

        $response->assertRedirect('/profile');
        $this->assertAuthenticated();

        $user = User::where('phone', $phone)->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);
    }

    public function test_web_logout_terminates_session_and_redirects_to_login(): void
    {
        $user = User::create([
            'name' => 'Active User',
            'email' => 'active@estilo.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_admin_login_page_does_not_render_admin_nav_when_logged_out(): void
    {
        $response = $this->get('/estilo-hq-console/login');

        $response->assertOk();
        $response->assertDontSee('Overview');
        $response->assertDontSee('Inventory');
        $response->assertDontSee('Executive Administration Suite');
        $this->assertGuest();
    }

    public function test_admin_logout_clears_session_and_disables_browser_cache(): void
    {
        $admin = User::create([
            'name' => 'Boutique Admin',
            'email' => 'admin@estilo.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post('/estilo-hq-console/logout');

        $response->assertRedirect('/estilo-hq-console/login');
        $response->assertHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, no-store, private');
        $this->assertGuest();
    }

    public function test_admin_can_view_web_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Boutique Admin',
            'email' => 'admin@estilo.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200)
            ->assertSee('Executive Administration Suite')
            ->assertSee('Estilo Management Console');
    }

    public function test_admin_can_create_update_and_delete_product(): void
    {
        $admin = User::create([
            'name' => 'Boutique Admin',
            'email' => 'admin@estilo.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
        ]);

        // Create
        $response = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Test Velvet Anarkali',
            'category' => 'Anarkali Suits',
            'fabric' => 'Pure Velvet',
            'price' => 3499,
            'description' => 'Rich velvet with intricate hand embroidery.',
        ]);

        $response->assertRedirect('/admin?tab=inventory');
        $product = \App\Models\Product::where('name', 'Test Velvet Anarkali')->first();
        $this->assertNotNull($product);

        // Update
        $updateResponse = $this->actingAs($admin)->post('/admin/products/' . $product->id, [
            'name' => 'Test Velvet Anarkali Deluxe',
            'price' => 3999,
            'fabric' => 'Silk Velvet',
            'category' => 'Anarkali Suits',
            'description' => 'Updated craft notes',
        ]);
        $updateResponse->assertRedirect('/admin?tab=inventory');
        $this->assertEquals('Test Velvet Anarkali Deluxe', $product->fresh()->name);

        // Delete
        $deleteResponse = $this->actingAs($admin)->delete('/admin/products/' . $product->id);
        $deleteResponse->assertRedirect('/admin?tab=inventory');
        $this->assertNull(\App\Models\Product::find($product->id));
    }

    public function test_admin_can_update_order_status_and_coupons(): void
    {
        $admin = User::create([
            'name' => 'Boutique Admin',
            'email' => 'admin@estilo.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
        ]);

        $order = \App\Models\Order::create([
            'order_no' => 'EST-TEST-001',
            'full_name' => 'Priya Nair',
            'email' => 'priya@example.com',
            'phone' => '9876543210',
            'address' => '12 Marine Drive',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pincode' => '400020',
            'subtotal' => 4500,
            'shipping' => 0,
            'discount' => 0,
            'total' => 4500,
            'currency' => 'INR',
            'status' => 'pending',
            'items' => json_encode([['est_id' => 'est-001', 'name' => 'Royal Saree', 'quantity' => 1, 'price' => 4500]]),
        ]);

        // Update Order
        $orderRes = $this->actingAs($admin)->post('/admin/orders/' . $order->id . '/status', [
            'status' => 'shipped',
        ]);
        $orderRes->assertRedirect('/admin?tab=orders');
        $this->assertEquals('shipped', $order->fresh()->status);

        // Create Coupon
        $couponRes = $this->actingAs($admin)->post('/admin/coupons', [
            'code' => 'DIWALI25',
            'title' => 'Diwali 25% Off',
            'discount_type' => 'percentage',
            'discount_value' => 25,
            'min_order_value' => 1999,
        ]);
        $couponRes->assertRedirect('/admin?tab=offers');
        $coupon = \App\Models\Coupon::where('code', 'DIWALI25')->first();
        $this->assertNotNull($coupon);

        // Toggle Coupon
        $toggleRes = $this->actingAs($admin)->post('/admin/coupons/' . $coupon->id . '/toggle');
        $toggleRes->assertRedirect('/admin?tab=offers');
        $this->assertFalse((bool)$coupon->fresh()->is_active);
    }

    public function test_admin_can_view_and_update_profile_and_then_logout(): void
    {
        $admin = User::create([
            'name' => 'Master Administrator',
            'email' => 'admin_test@estilo.com',
            'password' => Hash::make('AdminSecret123'),
            'role' => 'admin',
            'phone' => '9112233445',
        ]);

        // 1. Admin views dashboard and profile tab
        $viewRes = $this->actingAs($admin)->get('/admin?tab=profile');
        $viewRes->assertStatus(200)
            ->assertSee('Admin Profile')
            ->assertSee('Master Administrator')
            ->assertSee('admin_test@estilo.com');

        // 2. Admin updates profile information
        $updateRes = $this->actingAs($admin)->post('/admin/profile', [
            'name' => 'Lead Atelier Admin',
            'email' => 'admin_test@estilo.com',
            'phone' => '9988776655',
            'password' => 'NewAdminPass123',
        ]);

        $updateRes->assertRedirect('/admin?tab=overview');
        $this->assertEquals('Lead Atelier Admin', $admin->fresh()->name);
        $this->assertEquals('9988776655', $admin->fresh()->phone);
        $this->assertTrue(Hash::check('NewAdminPass123', $admin->fresh()->password));

        // 3. Admin logs out of session
        $logoutRes = $this->actingAs($admin)->post('/logout');
        $logoutRes->assertRedirect('/login');
        $this->assertGuest();

        // 4. Verify unauthenticated access is now blocked
        $blockedRes = $this->get('/admin');
        $blockedRes->assertRedirect('/login?role=admin');
    }

    public function test_profile_route_redirects_appropriately_based_on_role(): void
    {
        // 1. Guest is redirected to login
        $this->get('/profile')->assertRedirect('/login');

        // 2. Admin is redirected to /admin?tab=profile
        $admin = User::create([
            'name' => 'Store Admin',
            'email' => 'storeadmin@estilo.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
        ]);
        $this->actingAs($admin)->get('/profile')->assertRedirect('/admin?tab=profile');

        // 3. Sales Associate is redirected to /sales/dashboard
        $associate = User::create([
            'name' => 'Sales Partner',
            'email' => 'partner@estilo.com',
            'password' => Hash::make('Partner@123'),
            'role' => 'sales_associate',
            'referral_code' => 'ESTILO-TEST99',
        ]);
        $this->actingAs($associate)->get('/profile')->assertRedirect('/sales/dashboard');

        // 4. Customer views their profile page
        $customer = User::create([
            'name' => 'Ananya Sharma',
            'email' => 'ananya@estilo.com',
            'password' => Hash::make('Customer@123'),
            'role' => 'customer',
        ]);
        $res = $this->actingAs($customer)->get('/profile');
        $res->assertStatus(200)
            ->assertSee('Ananya Sharma')
            ->assertSee('Order History');
    }
}
