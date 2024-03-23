<?php

namespace App\Http\Middlewares;

use Closure;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware {
  public function handle(Request $request, Closure $next): Response {
    if (!auth()->check()) 
      return response()->json(ResponseUtils::build(401, "Unauthenticated", true, null), 401);

    $user = auth()->user();
    $isAdmin = $user->is_admin;
    $method = $request->method();

    if ($isAdmin == 0 && ($request->isMethod("put") || $request->isMethod("delete") || $request->isMethod("post")))
     return response()->json(ResponseUtils::build(403, "Forbidden, you are not allowed to update or delete anything", true, null));
      
    return $next($request);
  }
}