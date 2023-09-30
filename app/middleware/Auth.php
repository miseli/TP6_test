<?php
namespace app\middleware;

class Auth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        //http://localhost:8020/?name=cube
        if ($request->param('name') == 'cube') {
            return redirect('hello/cube');
        }

        return $next($request);
    }
}
