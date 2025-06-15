<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Pastikan file routes/api.php ada jika baris ini aktif
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) { // HANYA SATU BLOK INI UNTUK SEMUA KONFIGURASI MIDDLEWARE
        
        // 1. Daftarkan alias untuk route middleware di sini
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            // 'auth' => \App\Http\Middleware\Authenticate::class, // Biasanya sudah ter-handle otomatis oleh Laravel
            // 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // Biasanya sudah ter-handle otomatis
            // Tambahkan alias middleware lain jika ada
        ]);

        // 2. Tambahkan middleware untuk grup 'api' (misalnya untuk Sanctum)
        // Ini akan menambahkan middleware ke awal dari grup 'api'
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // Jika kamu butuh throttle untuk API, bisa ditambahkan di sini juga atau di route-nya langsung
            // 'throttle:api',
        ]);

        // 3. (Opsional) Jika kamu perlu menambahkan middleware ke grup 'web'
        // $middleware->web(append: [
        // \App\Http\Middleware\AnotherWebMiddleware::class,
        // ]);
        
        // 4. (Opsional) Jika kamu perlu menambahkan middleware global
        // $middleware->append(\App\Http\Middleware\GlobalMiddleware::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ... konfigurasi exception handling
        // $exceptions->dontReport([
        //     \App\Exceptions\YourCustomException::class,
        // ]);
        // $exceptions->report(function (Throwable $e) {
        //     // ...
        // });
    })->create();