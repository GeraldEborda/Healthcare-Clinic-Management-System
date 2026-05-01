<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Healthcare Clinic' }}</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #edf5ff;
            --sidebar: #183b8f;
            --sidebar-dark: #102a68;
            --sidebar-line: rgba(255, 255, 255, 0.12);
            --surface: #ffffff;
            --surface-soft: #eef6ff;
            --surface-tint: #e8f1ff;
            --text: #031b3d;
            --muted: #5b6f8c;
            --line: #b8d6ff;
            --line-soft: #d8e9ff;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-soft: #dbeafe;
            --success: #00a651;
            --success-soft: #dcfce7;
            --warning: #b77900;
            --warning-soft: #fef3c7;
            --danger: #dc2626;
            --danger-soft: #fee2e2;
            --shadow: 0 18px 40px rgba(24, 59, 143, 0.10);
            --radius: 8px;
            --sidebar-width: 320px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 15px;
            line-height: 1.5;
        }

        a { color: inherit; text-decoration: none; }

        .shell {
            width: 100%;
            min-height: 100vh;
            padding: 38px 40px 56px calc(var(--sidebar-width) + 40px);
        }

        .topbar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 20;
            width: var(--sidebar-width);
            padding: 30px 20px 18px;
            background: linear-gradient(180deg, var(--sidebar) 0%, var(--sidebar-dark) 100%);
            color: #fff;
            box-shadow: 18px 0 45px rgba(12, 33, 81, 0.18);
        }

        .topbar-inner {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .brand-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding: 0 10px 26px;
            border-bottom: 1px solid var(--sidebar-line);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .brand-mark {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 50%;
            background: #60a5fa;
            color: #fff;
            font-weight: 800;
            box-shadow: 0 16px 26px rgba(6, 20, 56, 0.22);
        }

        .brand h2 {
            margin: 0;
            color: #fff;
            font-size: 26px;
            line-height: 1.08;
            letter-spacing: 0;
        }

        .brand .muted {
            margin-top: 6px;
            color: rgba(255, 255, 255, 0.82);
            font-size: 14px;
        }

        .topbar .badge {
            background: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.9);
        }

        .nav {
            display: grid;
            gap: 8px;
        }

        .nav a {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 60px;
            padding: 0 20px;
            border-radius: var(--radius);
            color: rgba(255, 255, 255, 0.88);
            font-size: 18px;
            font-weight: 800;
            transition: background 160ms ease, color 160ms ease, transform 160ms ease;
        }

        .nav a:hover {
            background: rgba(255, 255, 255, 0.10);
            transform: translateX(2px);
        }

        .nav a.active {
            background: #60a5fa;
            color: #fff;
            box-shadow: 0 16px 30px rgba(21, 66, 157, 0.34);
        }

        .nav-icon {
            width: 24px;
            height: 24px;
            display: inline-grid;
            place-items: center;
            border: 2px solid currentColor;
            border-radius: 6px;
            font-size: 12px;
            line-height: 1;
            font-weight: 900;
        }

        .sidebar-user {
            margin-top: auto;
            padding: 18px 10px 0;
            border-top: 1px solid var(--sidebar-line);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 50px;
            height: 50px;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 50%;
            background: #60a5fa;
            color: #fff;
            font-weight: 800;
            font-size: 18px;
        }

        .sidebar-user strong {
            display: block;
            color: #fff;
            font-size: 16px;
        }

        .sidebar-user span {
            color: rgba(255, 255, 255, 0.78);
            font-size: 14px;
        }

        .grid { display: grid; gap: 22px; }
        .grid-2 { grid-template-columns: minmax(360px, 0.75fr) minmax(520px, 1.35fr); align-items: start; }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }

        .panel, .table-wrap, .stat {
            background: rgba(255, 255, 255, 0.97);
            border: 2px solid var(--line);
            border-radius: var(--radius);
            box-shadow: none;
        }

        .panel, .table-wrap { padding: 30px; }

        .stat {
            position: relative;
            overflow: hidden;
            min-height: 132px;
            padding: 28px 30px;
        }

        .stat::after {
            content: "";
            position: absolute;
            inset: auto -34px -42px auto;
            width: 106px;
            height: 106px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.12), rgba(6, 182, 212, 0.08));
        }

        .stat .muted {
            position: relative;
            font-weight: 800;
        }

        .stat strong {
            position: relative;
            display: block;
            margin-top: 26px;
            color: #001a42;
            font-size: clamp(28px, 3vw, 36px);
            line-height: 1;
            letter-spacing: 0;
            font-weight: 500;
        }

        .muted { color: var(--muted); }
        .meta { font-size: 14px; color: var(--muted); }
        .stack { display: grid; gap: 16px; }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .section-title {
            display: grid;
            gap: 4px;
        }

        .section-title p {
            margin: 0;
            color: var(--muted);
            font-size: 18px;
        }

        .record {
            padding: 22px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--surface-tint);
        }

        .page-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 28px;
        }

        .page-head h1, .page-head h2, .page-head h3, h3 {
            margin: 0;
            color: #001a42;
            letter-spacing: 0;
        }

        .page-head h1 { font-size: clamp(34px, 3vw, 42px); line-height: 1.08; }
        .page-head h3, h3 { font-size: 22px; }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 5px 12px;
            border-radius: 999px;
            background: var(--success-soft);
            color: var(--success);
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
        }

        .flash, .errors {
            padding: 14px 16px;
            border-radius: var(--radius);
            margin-bottom: 18px;
            border: 1px solid var(--line);
            background: #fff;
        }

        .flash { border-color: #bfdbfe; background: #eff6ff; color: #1d4ed8; }
        .errors { border-color: #fecaca; background: #fef2f2; color: #991b1b; }

        form { display: grid; gap: 14px; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .form-grid .full { grid-column: 1 / -1; }

        label {
            display: grid;
            gap: 8px;
            color: #001a42;
            font-size: 14px;
            font-weight: 800;
        }

        input, textarea, select {
            width: 100%;
            min-height: 50px;
            padding: 12px 16px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--surface-soft);
            color: var(--text);
            font: inherit;
            font-size: 16px;
            outline: none;
            transition: border-color 160ms ease, box-shadow 160ms ease, background 160ms ease;
        }

        input:focus, textarea:focus, select:focus {
            background: #fff;
            border-color: #60a5fa;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.22);
        }

        input::placeholder, textarea::placeholder { color: #7186a5; }
        textarea { min-height: 118px; resize: vertical; }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            min-height: 18px;
            accent-color: var(--primary);
        }

        label:has(input[type="checkbox"]) {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--surface-soft);
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin: 6px 0 28px;
            padding: 0;
            border: 0;
            background: transparent;
        }

        .filters .wide { grid-column: span 2; }

        .filters input,
        .filters select {
            background: var(--surface-soft);
            border-color: transparent;
        }

        button, .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            border: 1px solid transparent;
            border-radius: var(--radius);
            padding: 10px 18px;
            cursor: pointer;
            background: var(--primary);
            color: #fff;
            font-weight: 800;
            font-size: 16px;
            line-height: 1.2;
            transition: background 160ms ease, border-color 160ms ease, transform 160ms ease, box-shadow 160ms ease;
        }

        button:hover, .button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 14px 24px rgba(37, 99, 235, 0.18);
        }

        .button.secondary, button.secondary {
            background: #eff6ff;
            border-color: var(--line);
            color: var(--primary);
        }

        .button.secondary:hover, button.secondary:hover {
            background: #dbeafe;
            box-shadow: none;
        }

        .button.danger, button.danger {
            background: #eff6ff;
            border-color: var(--line);
            color: var(--danger);
        }

        .button.danger:hover, button.danger:hover {
            background: var(--danger);
            border-color: var(--danger);
            color: #fff;
            box-shadow: 0 12px 22px rgba(220, 38, 38, 0.16);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
        }

        thead th {
            background: transparent;
            color: #566b89;
            font-size: 15px;
            text-transform: none;
            letter-spacing: 0;
            font-weight: 800;
        }

        th, td {
            text-align: left;
            padding: 18px 20px;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        tbody tr {
            transition: background 140ms ease;
        }

        tbody tr:hover { background: #f8fbff; }
        tr:last-child td { border-bottom: 0; }

        td strong {
            color: #001a42;
            font-weight: 800;
            font-size: 17px;
        }

        .inline-form { display: inline; }

        .pagination { margin-top: 18px; }
        .pagination nav > div:first-child { display: none; }
        .pagination svg { width: 16px; height: 16px; }

        @media (max-width: 1180px) {
            .grid-2 { grid-template-columns: 1fr; }
        }

        @media (max-width: 920px) {
            .shell { padding: 18px 14px 40px; }
            .topbar {
                position: static;
                width: auto;
                margin: -18px -14px 24px;
                padding: 18px 14px;
                border-radius: 0;
            }
            .topbar-inner { gap: 16px; }
            .brand-row {
                padding: 0 0 16px;
                align-items: center;
            }
            .brand h2 { font-size: 22px; }
            .nav {
                display: flex;
                overflow-x: auto;
                padding-bottom: 4px;
            }
            .nav a {
                min-height: 46px;
                white-space: nowrap;
                font-size: 15px;
            }
            .sidebar-user { display: none; }
        }

        @media (max-width: 720px) {
            .page-head {
                align-items: flex-start;
                flex-direction: column;
            }
            .page-head h1 { font-size: 30px; }
            .section-title p { font-size: 16px; }
            .grid-3 { grid-template-columns: 1fr; }
            .filters .wide { grid-column: auto; }
            .form-grid { grid-template-columns: 1fr; }
            .form-grid .full { grid-column: auto; }
            .panel, .table-wrap { padding: 18px; }
            table, thead, tbody, th, td, tr { display: block; }
            thead { display: none; }
            tbody tr {
                padding: 12px 0;
                border-bottom: 1px solid var(--line);
            }
            tbody tr:last-child { border-bottom: 0; }
            td {
                padding: 8px 0;
                border-bottom: 0;
            }
            td::before {
                content: attr(data-label);
                display: block;
                margin-bottom: 3px;
                color: var(--muted);
                font-size: 12px;
                font-weight: 800;
            }
        }

        @media print {
            body {
                background: #fff;
                color: #000;
            }

            .shell {
                padding: 0;
            }

            .topbar,
            .print-actions,
            .button,
            button {
                display: none !important;
            }

            .panel, .table-wrap, .stat {
                border-color: #cbd5e1;
                box-shadow: none;
            }

            .grid-3 {
                grid-template-columns: repeat(3, 1fr);
            }

            table, thead, tbody, th, td, tr {
                display: revert;
            }

            th, td {
                padding: 8px;
                border-bottom: 1px solid #cbd5e1;
            }

            td::before {
                content: none;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <aside class="topbar">
            <div class="topbar-inner">
                <div class="brand-row">
                    <div class="brand">
                        <div class="brand-mark">HC</div>
                        <div>
                            <h2>HealthCare Clinic</h2>
                            <span class="sr-only">Healthcare Clinic Management System</span>
                            <div class="muted">Management System</div>
                        </div>
                    </div>
                    <span class="badge">{{ now()->format('M d') }}</span>
                </div>
                <nav class="nav" aria-label="Primary navigation">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="nav-icon">D</span> Dashboard</a>
                    <a href="{{ route('patients.index') }}" class="{{ request()->routeIs('patients.*') ? 'active' : '' }}"><span class="nav-icon">P</span> Patients</a>
                    <a href="{{ route('doctors.index') }}" class="{{ request()->routeIs('doctors.*') ? 'active' : '' }}"><span class="nav-icon">Dr</span> Doctors</a>
                    <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}"><span class="nav-icon">S</span> Services</a>
                    <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}"><span class="nav-icon">A</span> Appointments</a>
                    <a href="{{ route('inventory.index') }}" class="{{ request()->routeIs('inventory.*') ? 'active' : '' }}"><span class="nav-icon">I</span> Inventory</a>
                    <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}"><span class="nav-icon">B</span> Billing</a>
                </nav>
                <div class="sidebar-user">
                    <div class="avatar">AD</div>
                    <div>
                        <strong>Admin User</strong>
                        <span>admin@clinic.com</span>
                    </div>
                </div>
            </div>
        </aside>

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
