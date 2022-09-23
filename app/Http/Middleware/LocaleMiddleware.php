<?php

namespace App\Http\Middleware;

use App\Models\Locale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocaleMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 *
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		$supportedLocales = Locale::getLocales();

		$requestedLocale = $request->header('accept-language');
		$locale = $supportedLocales[config('app.locale')] ?? reset($supportedLocales); // get default locale or first supportedLocale

		// check if requestedLocale is a supported locale
		if (isset($supportedLocales[$requestedLocale])) {
			$locale = $supportedLocales[$requestedLocale];
		}
		else {
			// no directly locale name given, try to find any supported locale from header value
			foreach (explode(',', str_replace(';', ',', $requestedLocale)) as $value) {
				if (isset($supportedLocales[$value])) {
					$locale = $supportedLocales[$value];
					break;
				}
			}
		}

		App::setLocale($locale->languageCode);
		App::getFacadeRoot()->localeID = $locale->ID;

		return $next($request);
	}
}
