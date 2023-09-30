<?php
declare (strict_types = 1);

namespace app\middleware;

require  __DIR__.'/../../../common/common.php';
class Check
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

            // debugger($request->cookie());
            // debugger($request->env());
            debugger($request->header());
            // debugger($request);
            // return redirect('/hello/cube');
        }

        return $next($request);
    }
}
