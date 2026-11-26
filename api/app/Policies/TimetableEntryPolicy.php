<?php

namespace App\Policies;

use App\Models\TimetableEntry;
use App\Models\User;

class TimetableEntryPolicy
{
    // 時間割の行を消せるのは、その行の持ち主だけ
    public function delete(User $user, TimetableEntry $timetableEntry): bool
    {
        return $user->id == $timetableEntry->user_id;
    }
}
