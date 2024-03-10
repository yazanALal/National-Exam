<?php

namespace App\Http\Middleware;

use App\Http\Requests\showFromCollegeRequest;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use Closure;
use Illuminate\Http\Request;

class verifyCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    use GeneralTrait;
    use UserTrait;
    public function handle(Request $request, Closure $next)
    {
        $college_uuid = $this->getUserCollegeUuid($request);
        
        if ($college_uuid != (string)($request->college_uuid)) {

            return $this->unAuthorizeResponse();
        }
        return $next($request);
    }
}
