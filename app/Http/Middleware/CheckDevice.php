<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDevice
{
    public function handle(Request $request, Closure $next)
    {
        $userAgent = $request->header('User-Agent');

        if (strpos($userAgent, 'Android') !== false || strpos($userAgent, 'Windows') !== false) {
            return redirect()->to('https://play.google.com/store/apps/details?id=com.mydatalife.wellbeing360&hl=en_US');
        } elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false || strpos($userAgent, 'Macintosh') !== false) {
            return redirect()->to('https://apps.apple.com/us/app/360-wellbeing/id6443438273');
        }else {
            return redirect()->to('https://play.google.com/store/apps/details?id=com.mydatalife.wellbeing360&hl=en_US');
        }

    }
}
