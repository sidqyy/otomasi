<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Otomasi Florist') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Outfit', sans-serif !important; }
            
            .animated-bg {
                background: linear-gradient(-45deg, #022c22, #064e3b, #047857, #065f46);
                background-size: 400% 400%;
                animation: gradientBG 15s ease infinite;
                position: relative;
            }
            
            @keyframes gradientBG {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            .animated-bg::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2310b981' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
                z-index: 0;
            }

            .content-wrapper {
                position: relative;
                z-index: 10;
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.5);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255,255,255,0.1) inset;
                border-radius: 1.5rem;
                transform: translateY(0);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .glass-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255,255,255,0.2) inset;
            }

            .premium-input {
                background: #f8fafc !important;
                border: 2px solid transparent !important;
                transition: all 0.3s ease !important;
                box-shadow: inset 0 2px 4px rgba(0,0,0,0.02) !important;
            }
            .premium-input:focus {
                background: #ffffff !important;
                border-color: #10b981 !important;
                box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
                outline: none !important;
            }
            
            .premium-btn {
                background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
                transition: all 0.3s ease !important;
                position: relative;
                overflow: hidden;
                border: none !important;
            }
            .premium-btn:hover {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
                transform: translateY(-2px);
                box-shadow: 0 10px 20px -10px rgba(16, 185, 129, 0.6) !important;
            }
            .premium-btn:active {
                transform: translateY(1px);
            }

            .logo-icon {
                animation: float 6s ease-in-out infinite;
                filter: drop-shadow(0 0 10px rgba(52, 211, 153, 0.4));
            }
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-8px); }
                100% { transform: translateY(0px); }
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased animated-bg">
        <div class="min-h-screen flex flex-col justify-center items-center py-10 content-wrapper px-4">
            <div>
                <a href="/">
                    <h1 class="text-4xl sm:text-5xl font-bold tracking-wider text-white flex items-center gap-3 drop-shadow-lg mb-2">
                        <svg class="w-14 h-14 text-emerald-400 logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4M4 4l16 16M4 20L20 4"></path></svg>
                        FLORIST<span class="text-emerald-400">BOT</span>
                    </h1>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-10 px-8 py-10 glass-card">
                {{ $slot }}
            </div>
            
            <p class="mt-10 text-emerald-100/50 text-sm font-medium tracking-wide">
                &copy; {{ date('Y') }} FloristBot Automations.
            </p>
        </div>
    </body>
</html>
