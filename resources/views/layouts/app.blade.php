<!DOCTYPE html>
<html lang="id" class="scroll-smooth" data-type="service" data-radius="soft">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="page-type" content="service">
    <meta name="site-kind" content="other">
    <title>@yield('title', 'Simanis — BUMDes Payungsari')</title>

    {{-- Taste Skill: Figtree + Offwhite-Bottle-Yellow palette --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --p-bg: #f7f7f7;
            --p-surface: #ebebeb;
            --p-ink: #0d2a1c;
            --p-muted: #5f6b64;
            --p-accent: #f6e016;
            --p-accent-ink: #0d2a1c;
            --p-action: #0d2a1c;
            --p-action-ink: #f7f7f7;
            --p-inv-bg: #0d2a1c;
            --p-inv-surface: #1c3629;
            --p-inv-ink: #f7f7f7;
            --p-inv-muted: #97a39c;
            --p-inv-accent: #f6e016;
            --p-inv-accent-ink: #0d2a1c;
            --p-inv-action: #f6e016;
            --p-inv-action-ink: #0d2a1c;
            --font: "Figtree", system-ui, sans-serif;
            --radius: 8px;
            --radius-sm: 4px;
            --radius-lg: 14px;
            --edge: clamp(20px, 5vw, 80px);
            --content-max: 1160px;
            --transition: 220ms cubic-bezier(.4,0,.2,1);
        }

        body {
            font-family: var(--font);
            background: var(--p-bg);
            color: var(--p-ink);
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
        }

        [x-cloak] { display: none !important; }

        /* Utility */
        .container { max-width: var(--content-max); margin: 0 auto; padding: 0 var(--edge); }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            font-family: var(--font); font-weight: 500; font-size: 14px;
            padding: 10px 20px; border-radius: var(--radius); border: none;
            cursor: pointer; transition: transform var(--transition), background var(--transition), box-shadow var(--transition);
            text-decoration: none; white-space: nowrap;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn-primary { background: var(--p-action); color: var(--p-action-ink); }
        .btn-primary:hover { background: #1c3a28; box-shadow: 0 4px 12px rgba(13,42,28,.18); }
        .btn-accent { background: var(--p-accent); color: var(--p-accent-ink); }
        .btn-accent:hover { background: #e8d400; box-shadow: 0 4px 12px rgba(246,224,22,.35); }
        .btn-ghost { background: transparent; color: var(--p-ink); border: 1.5px solid rgba(13,42,28,.2); }
        .btn-ghost:hover { background: var(--p-surface); border-color: rgba(13,42,28,.4); }
        .btn-sm { padding: 6px 14px; font-size: 13px; }
        .btn-danger { background: #fef2f2; color: #b91c1c; border: 1.5px solid #fecaca; }
        .btn-danger:hover { background: #fee2e2; }
        .btn-wa {
            background: #25D366; color: #fff;
            display: inline-flex; align-items: center; gap: 8px;
            font-weight: 500; font-size: 14px;
            padding: 11px 22px; border-radius: var(--radius); border: none;
            cursor: pointer; text-decoration: none;
            transition: transform var(--transition), box-shadow var(--transition);
        }
        .btn-wa:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(37,211,102,.3); }

        /* Cards */
        .card {
            background: #fff; border-radius: var(--radius-lg);
            border: 1px solid rgba(13,42,28,.08);
            box-shadow: 0 1px 3px rgba(13,42,28,.06);
            transition: box-shadow var(--transition), transform var(--transition);
        }
        .card:hover { box-shadow: 0 4px 16px rgba(13,42,28,.1); transform: translateY(-1px); }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 600; padding: 3px 10px;
            border-radius: 999px; letter-spacing: .02em; text-transform: uppercase;
        }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-red { background: #fee2e2; color: #b91c1c; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-orange { background: #fff7ed; color: #c2410c; }
        .badge-purple { background: #f3e8ff; color: #7e22ce; }
        .badge .dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

        /* Tables */
        .data-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        .data-table thead th {
            padding: 10px 16px; text-align: left;
            font-size: 11px; font-weight: 600; letter-spacing: .06em;
            text-transform: uppercase; color: var(--p-muted);
            background: var(--p-bg); border-bottom: 1px solid var(--p-surface);
        }
        .data-table thead th.text-right { text-align: right; }
        .data-table tbody td { padding: 11px 16px; border-bottom: 1px solid rgba(13,42,28,.05); vertical-align: middle; }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: rgba(13,42,28,.02); }
        .data-table td.text-right { text-align: right; }
        .data-table td.mono { font-variant-numeric: tabular-nums; letter-spacing: 0; }
        .data-table td.nota-num { font-family: "SF Mono", "Fira Code", monospace; font-size: 11px; color: #15803d; }

        /* Form controls */
        .form-label { display: block; font-size: 12px; font-weight: 500; color: var(--p-muted); margin-bottom: 5px; }
        .form-control {
            width: 100%; border: 1.5px solid rgba(13,42,28,.15);
            border-radius: var(--radius); padding: 9px 12px; font-size: 13.5px;
            font-family: var(--font); background: #fff; color: var(--p-ink);
            transition: border-color var(--transition), box-shadow var(--transition);
            -webkit-appearance: none;
        }
        .form-control:focus { outline: none; border-color: var(--p-accent); box-shadow: 0 0 0 3px rgba(246,224,22,.2); }
        .form-control::placeholder { color: #aab8b0; }
        .form-control-sm { padding: 7px 10px; font-size: 13px; }
        .form-error { font-size: 11px; color: #b91c1c; margin-top: 4px; }

        /* Flash messages */
        .flash { padding: 12px 16px; border-radius: var(--radius); font-size: 13.5px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 10px; }
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }

        /* Stat number */
        .stat-num { font-size: 26px; font-weight: 700; line-height: 1; letter-spacing: -0.02em; }
        .stat-label { font-size: 11.5px; color: var(--p-muted); margin-top: 4px; font-weight: 500; letter-spacing: .03em; text-transform: uppercase; }

        /* Section / page-level heading */
        .page-heading { font-size: 22px; font-weight: 700; letter-spacing: -0.02em; }
        .page-sub { font-size: 13.5px; color: var(--p-muted); margin-top: 2px; }

        /* Smooth hover on table rows */
        .data-table tbody tr { transition: background var(--transition); }

        /* Pagination */
        .pagination { display: flex; gap: 4px; }
        .pagination a, .pagination span { padding: 5px 10px; border-radius: 6px; font-size: 13px; border: 1px solid var(--p-surface); color: var(--p-ink); text-decoration: none; transition: background var(--transition); }
        .pagination a:hover { background: var(--p-surface); }
        .pagination .active span, .pagination span.current { background: var(--p-action); color: var(--p-action-ink); border-color: var(--p-action); }
    </style>
    @stack('head')
</head>
<body>
@yield('content')
</body>
</html>
