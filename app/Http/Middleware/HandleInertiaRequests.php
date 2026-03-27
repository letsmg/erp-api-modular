<?php

namespace App\Http\Middleware;

use App\Modules\Product\Models\Seo;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'error' => fn () => $request->session()->get('error'),
                'success' => fn () => $request->session()->get('success'),
            ],
            'store_seo' => cache()->remember('store_seo', 3600, function () {
                return [
                    'title' => 'ERP Vue Laravel',
                    'description' => 'ERP modular com frontend Vue e backend Laravel.',
                    'keywords' => 'erp, laravel, vuejs',
                    'h1' => 'ERP Vue Laravel',
                ];
            }),
        ]);
    }
}
