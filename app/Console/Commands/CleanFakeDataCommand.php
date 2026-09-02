<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanFakeDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clean-fake-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all fake seeded users and fake demo orders from the database while preserving real admin and customer accounts.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info(' Starting Database Cleanup for Fake Users & Orders...');

        // 1. Clean Fake Users
        $keepEmails = ['admin@estilo.com', 'shelaromkar313@gmail.com'];
        $fakeUsers = User::whereNotIn('email', $keepEmails)->get();
        $this->info("Found " . $fakeUsers->count() . " fake users to delete.");

        foreach ($fakeUsers as $u) {
            $this->line("  - Removing User ID {$u->id}: {$u->name} ({$u->email})");
            DB::table('orders')->where('user_id', $u->id)->update(['user_id' => null]);
            DB::table('reviews')->where('user_id', $u->id)->delete();
            $u->delete();
        }

        // 2. Clean Fake Orders
        $fakeOrders = Order::where('email', '!=', 'shelaromkar313@gmail.com')->get();
        $this->info("Found " . $fakeOrders->count() . " fake demo orders to delete.");

        foreach ($fakeOrders as $o) {
            $this->line("  - Removing Order ID {$o->id} ({$o->order_no}): Customer {$o->full_name} ({$o->email})");
            if (Schema::hasTable('order_items')) {
                DB::table('order_items')->where('order_id', $o->id)->delete();
            }
            $o->delete();
        }

        // 3. Link real orders with user_id
        $unlinkedOrders = Order::whereNull('user_id')->get();
        foreach ($unlinkedOrders as $ord) {
            $matchedUser = User::where('email', $ord->email)->orWhere('phone', $ord->phone)->first();
            if ($matchedUser) {
                $ord->user_id = $matchedUser->id;
                $ord->save();
                $this->info("  - Linked Order {$ord->order_no} to User ID {$matchedUser->id} ({$matchedUser->email})");
            }
        }

        $this->info(" Database cleanup completed successfully!");
        return Command::SUCCESS;
    }
}
