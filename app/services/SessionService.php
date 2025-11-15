<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class SessionService
{
    /**
     * Get all active sessions for a user
     */
    public function getUserSessions($userId)
    {
        $sessions = DB::table('sessions')
            ->where('user_id', $userId)
            ->orderBy('last_activity', 'desc')
            ->get();

        $agent = new Agent();

        return $sessions->map(function ($session) use ($agent) {
            $agent->setUserAgent($session->user_agent);

            return [
                'id' => $session->id,
                'ip_address' => $session->ip_address,
                'browser' => $agent->browser(),
                'platform' => $agent->platform(),
                'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    /**
     * Logout a specific session
     */
    public function logoutSession($sessionId)
    {
        DB::table('sessions')->where('id', $sessionId)->delete();
    }
}
