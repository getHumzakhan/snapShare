<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SearchPost
{
    public function handle(Request $request, Closure $next)
    {
        $search['filters'] = $request->input('data');
        $search['filters']['user_id'] = strval($request['_id']);
        unset($request['_id']);

        if($request->filled('data.creation_date'))
        {
            $search['filters']['creation_date'] = strtotime($search['filters']['creation_date']);
        }
        if($request->filled('data.creation_time'))
        {
            $search['filters']['creation_time'] = strtotime($search['filters']['creation_time']);
        }

        $request = $request->merge($search);
        return $next($request);
    }
}
