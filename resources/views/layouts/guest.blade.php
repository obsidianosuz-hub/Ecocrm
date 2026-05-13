<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Obsidian OS') }} | Gateway</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --bg-space: #020205;
                --neon-cyan: #00ffcc;
                --neon-purple: #b026ff;
                --neon-pink: #ff007f;
                --glass-bg: rgba(10, 10, 20, 0.6);
                --glass-border: rgba(255, 255, 255, 0.05);
                --glass-blur: blur(20px);
            }

            body {
                background-color: var(--bg-space);
                color: #ffffff;
                font-family: 'Outfit', sans-serif;
                min-height: 100vh;
                margin: 0;
                padding: 0;
                overflow-x: hidden;
                position: relative;
            }

            /* Stars Background */
            .space-bg {
                position: fixed;
                inset: 0;
                background: radial-gradient(ellipse at bottom, #1B2735 0%, #090A0F 100%);
                z-index: -2;
            }

            .stars-container {
                position: fixed;
                inset: 0;
                z-index: -1;
                background: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1231630/stars.png') repeat;
                opacity: 0.5;
                animation: starMove 200s linear infinite;
            }

            @keyframes starMove {
                from { background-position: 0 0; }
                to { background-position: -10000px 5000px; }
            }

            .nebula {
                position: fixed;
                width: 100vw;
                height: 100vh;
                top: 0;
                left: 0;
                z-index: -1;
                background: 
                    radial-gradient(circle at 20% 30%, rgba(176, 38, 255, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 70%, rgba(0, 255, 204, 0.1) 0%, transparent 50%);
                filter: blur(80px);
                animation: nebulaPulse 15s infinite alternate;
            }

            @keyframes nebulaPulse {
                0% { opacity: 0.5; transform: scale(1); }
                100% { opacity: 0.8; transform: scale(1.2); }
            }

            .glass-card {
                background: var(--glass-bg);
                backdrop-filter: var(--glass-blur);
                border: 1px solid var(--glass-border);
                border-radius: 40px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8), inset 0 0 20px rgba(255,255,255,0.02);
                position: relative;
                overflow: hidden;
            }

            .btn-neon {
                background: linear-gradient(45deg, var(--neon-cyan), var(--neon-purple));
                color: #000;
                border-radius: 20px;
                font-weight: 800;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                box-shadow: 0 0 20px rgba(0, 255, 204, 0.3);
            }

            .btn-neon:hover {
                transform: translateY(-2px) scale(1.02);
                box-shadow: 0 0 30px rgba(0, 255, 204, 0.6);
            }

            .input-ios {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 20px;
                padding: 16px 24px;
                color: #fff;
                transition: all 0.3s ease;
            }

            .input-ios:focus {
                background: rgba(255, 255, 255, 0.05);
                border-color: var(--neon-cyan);
                box-shadow: 0 0 15px rgba(0, 255, 204, 0.1);
                outline: none;
            }

            .scan-line {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 2px;
                background: var(--neon-cyan);
                box-shadow: 0 0 15px var(--neon-cyan);
                z-index: 10;
                animation: scan 3s linear infinite;
            }

            @keyframes scan { 100% { top: 100%; } }
        </style>
    </head>
    <body class="antialiased">
        <div class="space-bg"></div>
        <div class="stars-container"></div>
        <div class="nebula"></div>
        
        <div class="relative w-full min-h-screen flex items-center justify-center p-4 sm:p-8">
            @yield('content')
            {{ $slot ?? '' }}
        </div>
    </body>
</html>
