<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — Status</title>
    <meta name="description" content="System status dashboard for {{ config('app.name') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0a0a0f;
            --surface: #12121a;
            --surface-hover: #1a1a26;
            --border: #1e1e2e;
            --text: #e4e4ed;
            --text-muted: #6b6b80;
            --accent: #818cf8;
            --accent-glow: rgba(129, 140, 248, 0.15);
            --green: #34d399;
            --green-glow: rgba(52, 211, 153, 0.15);
            --red: #f87171;
            --red-glow: rgba(248, 113, 113, 0.15);
            --yellow: #fbbf24;
            --yellow-glow: rgba(251, 191, 36, 0.15);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            overflow: hidden;
        }

        /* Ambient background glow */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at 30% 20%, rgba(129, 140, 248, 0.04) 0%, transparent 50%),
                        radial-gradient(ellipse at 70% 80%, rgba(52, 211, 153, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            width: 100%;
            max-width: 540px;
            position: relative;
            z-index: 1;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--accent), #6366f1);
            margin-bottom: 1.25rem;
            box-shadow: 0 0 30px var(--accent-glow);
            animation: pulse-glow 3s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 30px var(--accent-glow); }
            50% { box-shadow: 0 0 50px var(--accent-glow), 0 0 80px rgba(129, 140, 248, 0.05); }
        }

        .logo svg {
            width: 28px;
            height: 28px;
            color: white;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            margin-bottom: 0.375rem;
        }

        .header p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        /* Cards */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 0.75rem;
            transition: all 0.2s ease;
        }

        .card:hover {
            background: var(--surface-hover);
            border-color: rgba(129, 140, 248, 0.15);
        }

        .card-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-left {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .card-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .card-icon svg {
            width: 18px;
            height: 18px;
        }

        .card-icon.green  { background: var(--green-glow); color: var(--green); }
        .card-icon.red    { background: var(--red-glow);   color: var(--red); }
        .card-icon.accent { background: var(--accent-glow); color: var(--accent); }
        .card-icon.yellow { background: var(--yellow-glow); color: var(--yellow); }

        .card-label {
            font-size: 0.8125rem;
            font-weight: 600;
            margin-bottom: 0.125rem;
        }

        .card-detail {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Status badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.3rem 0.65rem;
            border-radius: 50px;
        }

        .badge.ok {
            background: var(--green-glow);
            color: var(--green);
        }

        .badge.fail {
            background: var(--red-glow);
            color: var(--red);
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .footer span {
            color: var(--accent);
            font-weight: 500;
        }

        /* Animate in */
        .card {
            opacity: 0;
            transform: translateY(12px);
            animation: fadeUp 0.4s ease forwards;
        }
        .card:nth-child(1) { animation-delay: 0.05s; }
        .card:nth-child(2) { animation-delay: 0.1s; }
        .card:nth-child(3) { animation-delay: 0.15s; }
        .card:nth-child(4) { animation-delay: 0.2s; }
        .card:nth-child(5) { animation-delay: 0.25s; }

        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/>
                </svg>
            </div>
            <h1>{{ config('app.name') }}</h1>
            <p>System Status Dashboard</p>
        </div>

        <div class="cards">
            {{-- MongoDB Connection --}}
            <div class="card" id="status-db">
                <div class="card-row">
                    <div class="card-left">
                        <div class="card-icon {{ $dbOk ? 'green' : 'red' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-label">MongoDB</div>
                            <div class="card-detail">{{ $dbOk ? $dbName : $dbError }}</div>
                        </div>
                    </div>
                    <div class="badge {{ $dbOk ? 'ok' : 'fail' }}">
                        <span class="badge-dot"></span>
                        {{ $dbOk ? 'Connected' : 'Error' }}
                    </div>
                </div>
            </div>

            {{-- Laravel Version --}}
            <div class="card" id="status-laravel">
                <div class="card-row">
                    <div class="card-left">
                        <div class="card-icon accent">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-label">Laravel</div>
                            <div class="card-detail">v{{ app()->version() }}</div>
                        </div>
                    </div>
                    <div class="badge ok">
                        <span class="badge-dot"></span>
                        Running
                    </div>
                </div>
            </div>

            {{-- PHP Version --}}
            <div class="card" id="status-php">
                <div class="card-row">
                    <div class="card-left">
                        <div class="card-icon accent">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-label">PHP</div>
                            <div class="card-detail">v{{ PHP_VERSION }}</div>
                        </div>
                    </div>
                    <div class="badge ok">
                        <span class="badge-dot"></span>
                        Active
                    </div>
                </div>
            </div>

            {{-- Environment --}}
            <div class="card" id="status-env">
                <div class="card-row">
                    <div class="card-left">
                        <div class="card-icon yellow">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-label">Environment</div>
                            <div class="card-detail">{{ config('app.env') }} &middot; debug {{ config('app.debug') ? 'on' : 'off' }}</div>
                        </div>
                    </div>
                    <div class="badge ok">
                        <span class="badge-dot"></span>
                        Loaded
                    </div>
                </div>
            </div>

            {{-- Reverb / Broadcasting --}}
            <div class="card" id="status-reverb">
                <div class="card-row">
                    <div class="card-left">
                        <div class="card-icon accent">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.348 14.652a3.75 3.75 0 0 1 0-5.304m5.304 0a3.75 3.75 0 0 1 0 5.304m-7.425 2.121a6.75 6.75 0 0 1 0-9.546m9.546 0a6.75 6.75 0 0 1 0 9.546M5.106 18.894c-3.808-3.807-3.808-9.98 0-13.788m13.788 0c3.808 3.807 3.808 9.98 0 13.788M12 12h.008v.008H12V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="card-label">Reverb</div>
                            <div class="card-detail">{{ config('reverb.servers.reverb.host') }}:{{ config('reverb.servers.reverb.port') }}</div>
                        </div>
                    </div>
                    <div class="badge ok">
                        <span class="badge-dot"></span>
                        Configured
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            Checked at <span>{{ now()->format('H:i:s') }}</span> &middot; {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
