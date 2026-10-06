<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * UIDs used to be "10000{id}" (sequential). New accounts now get random UIDs.
 * This migration never changes an existing UID: it only fills accounts that
 * have none, then indexes the column (unique when the data allows it) so UID
 * lookups (transfers, admin search) stay fast and duplicates can't appear.
 */
return new class extends Migration
{
    private string $unique = 'users_uid_unique';
    private string $plain  = 'users_uid_index';

    public function up(): void
    {
        DB::table('users')->where(fn ($q) => $q->whereNull('uid')->orWhere('uid', ''))->orderBy('id')
            ->each(fn ($u) => DB::table('users')->where('id', $u->id)->update(['uid' => User::generateUid()]));

        if ($this->has($this->unique) || $this->has($this->plain)) {
            return;
        }

        $duplicates = DB::table('users')->select('uid')->groupBy('uid')->havingRaw('COUNT(*) > 1')->exists();
        Schema::table('users', fn ($t) => $duplicates
            ? $t->index('uid', $this->plain)   // keep old data untouched; just speed up lookups
            : $t->unique('uid', $this->unique));
    }

    public function down(): void
    {
        foreach ([$this->unique, $this->plain] as $name) {
            if ($this->has($name)) {
                Schema::table('users', fn ($t) => $t->dropIndex($name));
            }
        }
    }

    private function has(string $name): bool
    {
        return DB::table('information_schema.statistics')->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'users')->where('index_name', $name)->exists();
    }
};
