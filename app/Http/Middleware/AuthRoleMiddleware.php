<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepositoryInterface;

class AuthRoleMiddleware
{
    /**
     * @var UserRepositoryInterface|\App\Repositories\Repository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roleID)
    {
        if (Auth::check() && Auth::user()->isActive) {
            $userID = Auth::user()->id;
            //check Roles by User
            $hasRole = $this->userRepository->getRolesIDByUserID($userID, $roleID);

            if ($hasRole) {
                return $next($request);
            }

            return redirect('/login');
        } else {
            return redirect('/login');
        }
    }
}
