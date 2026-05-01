<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Healthcare Clinic' }}</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f4f7f8;
            --panel: #ffffff;
            --panel-soft: #eef4f3;
            --text: #17313a;
            --muted: #5d7680;
            --line: #d7e3e4;
            --accent: #0e7490;
            --accent-soft: #e0f2fe;
            --danger: #b91c1c;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(180deg, #ecfeff 0%, var(--bg) 24%);
            color: var(--text);
        }
        a { color: inherit; text-decoration: none; }
        .shell { max-width: 1180px; margin: 0 auto; padding: 24px 16px 48px; }
        .topbar, .panel, .stat, .table-wrap { background: var(--panel); border: 1px solid var(--line); border-radius: 16px; }
        .topbar { padding: 18px; margin-bottom: 20px; }
        .nav { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; }
        .nav a {
            padding: 10px 14px;
            border-radius: 999px;
            background: var(--panel-soft);
            color: var(--muted);
            font-size: 14px;
        }
        .nav a.active { background: var(--accent); color: #fff; }
        .grid { display: grid; gap: 16px; }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
        .panel, .table-wrap, .stat { padding: 18px; }
        .stat strong { display: block; font-size: 28px; margin-top: 8px; }
        .muted { color: var(--muted); }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 12px;
            font-weight: bold;
        }
        .stack { display: grid; gap: 12px; }
        .page-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 16px; }
        .page-head h1, .page-head h2, h3 { margin: 0; }
        .flash, .errors {
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            border: 1px solid var(--line);
            background: #fff;
        }
        .flash { border-color: #bae6fd; background: #f0f9ff; color: #075985; }
        .errors { border-color: #fecaca; background: #fef2f2; color: #991b1b; }
        form { display: grid; gap: 12px; }
        label { display: grid; gap: 6px; font-size: 14px; color: var(--muted); }
        input, textarea, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #c9d6d8;
            border-radius: 10px;
            background: #fff;
            color: var(--text);
        }
        textarea { min-height: 96px; resize: vertical; }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin: 12px 0 16px;
        }
        .filters .wide { grid-column: span 2; }
        button, .button {
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            cursor: pointer;
            background: var(--accent);
            color: #fff;
            font-weight: bold;
        }
        .button.secondary, button.secondary { background: var(--panel-soft); color: var(--text); }
        .button.danger, button.danger { background: var(--danger); }
        .link-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 12px 10px; border-bottom: 1px solid #edf2f3; vertical-align: top; }
        th { color: var(--muted); font-size: 13px; }
        tr:last-child td { border-bottom: 0; }
        .inline-form { display: inline; }
        .meta { font-size: 13px; color: var(--muted); }
        .pagination { margin-top: 14px; }
        .pagination nav > div:first-child { display: none; }
        .pagination svg { width: 16px; height: 16px; }
        @media (max-width: 720px) {
            .page-head { align-items: flex-start; flex-direction: column; }
            .filters .wide { grid-column: auto; }
            table, thead, tbody, th, td, tr { display: block; }
            thead { display: none; }
            td { padding-left: 0; padding-right: 0; }
            td::before {
                content: attr(data-label);
                display: block;
                font-size: 12px;
                color: var(--muted);
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <div class="topbar">
            <div class="page-head">
                <div>
                    <h2>Healthcare Clinic Management System</h2>
                    <div class="muted">Minimal operations dashboard for patients, doctors, services, bookings, and billing.</div>
                </div>
                <span class="badge">{{ now()->format('M d, Y') }}</span>
            </div>
            <div class="nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('patients.index') }}" class="{{ request()->routeIs('patients.*') ? 'active' : '' }}">Patients</a>
                <a href="{{ route('doctors.index') }}" class="{{ request()->routeIs('doctors.*') ? 'active' : '' }}">Doctors</a>
                <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}">Services</a>
                <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}">Appointments</a>
                <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}">Transactions</a>
            </div>
        </div>

        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
