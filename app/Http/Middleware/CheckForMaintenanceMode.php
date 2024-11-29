<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Middleware;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Symfony\Component\HttpFoundation\IpUtils;
use App\model\Users\MaintenanceMode;

class CheckForMaintenanceMode extends Middleware
{
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    public function handle($request, Closure $next)
    {
        $ipaddress = $this->get_client_ip();

        $get_mode = MaintenanceMode::where('allowed_ips', '!=', 'null')->where('status', 'enable')->first();
        $get_mip = [];

        if (!empty($get_mode)) {
            $get_mip = explode(',', $get_mode->allowed_ips);
        }

        if ($this->app->isDownForMaintenance()) {
            if (in_array($ipaddress, $get_mip)) {
                return $next($request);
            }

            if ($this->inExceptArray($request)) {
                return $next($request);
            }

            $data = json_decode(file_get_contents($this->app->storagePath() . '/framework/down'), true);
            return response()->view('errors.503', ['message' => $get_mode], 503);
        }

        return $next($request);
    }

    public function get_client_ip() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
}
