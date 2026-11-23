@extends('layouts.app')

@section('styles')
    <style>
        /* ============================================================
                   PREMIUM NEON DARK UI - COMPLETE SET
                   ============================================================ */

        /* Background Area Utama */
        .content-wrapper {
            background-color: #0d0d0f !important;
            /* Hitam Deep Space */
            background-image:
                radial-gradient(circle at 0% 0%, rgba(99, 102, 241, 0.15) 0%, transparent 35%),
                radial-gradient(circle at 100% 100%, rgba(244, 63, 94, 0.1) 0%, transparent 35%) !important;
        }

        /* Glassmorphism Card Base */
        .stat-card-neon {
            background: rgba(255, 255, 255, 0.03) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 28px !important;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stat-card-neon:hover {
            transform: translateY(-12px);
            background: rgba(255, 255, 255, 0.07) !important;
            border: 1px solid rgba(255, 255, 255, 0.18) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        <style>
        /* ============================================================w
               PREMIUM NEON DARK UI - COMPLETE SET
               ============================================================ */

        /* Efek Pendaran Cahaya (Glow) */
        .stat-card-neon::before {
            content: "";
            position: absolute;
            top: -20%;
            right: -20%;
            width: 70%;
            height: 70%;
            background: radial-gradient(circle, var(--glow-color), transparent 70%);
            opacity: 0.12;
            pointer-events: none;
        }

        /* Icon Box Neon */
        .icon-box-neon {
            width: 55px;
            height: 55px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
        }

        /* Warna Variasi Neon */
        .neon-purple {
            --glow-color: #a855f7;
        }

        .neon-purple .icon-box-neon {
            background: linear-gradient(135deg, #a855f7, #6366f1);
        }

        .neon-purple .btn-neon {
            background: linear-gradient(90deg, #9333ea, #7e22ce);
        }

        .neon-orange {
            --glow-color: #fb923c;
        }

        .neon-orange .icon-box-neon {
            background: linear-gradient(135deg, #fb923c, #f97316);
        }

        .neon-orange .btn-neon {
            background: linear-gradient(90deg, #ea580c, #c2410c);
        }

        .neon-green {
            --glow-color: #22c55e;
        }

        .neon-green .icon-box-neon {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .neon-green .btn-neon {
            background: linear-gradient(90deg, #16a34a, #15803d);
        }

        .neon-blue {
            --glow-color: #3b82f6;
        }

        .neon-blue .icon-box-neon {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .neon-blue .btn-neon {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
        }

        .neon-red {
            --glow-color: #ef4444;
        }

        .neon-red .icon-box-neon {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .neon-red .btn-neon {
            background: linear-gradient(90deg, #dc2626, #b91c1c);
        }

        .neon-yellow {
            --glow-color: #eab308;
        }

        .neon-yellow .icon-box-neon {
            background: linear-gradient(135deg, #eab308, #ca8a04);
        }

        .neon-yellow .btn-neon {
            background: linear-gradient(90deg, #eab308, #ca8a04);
        }

        /* Button Styling */
        .btn-neon {
            border: none;
            border-radius: 14px;
            padding: 10px 20px;
            color: white !important;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            transition: all 0.3s;
            margin-top: 15px;
        }

        .btn-neon:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px var(--glow-color);
            filter: brightness(1.1);
        }

        /* Teks Styling */
        .stat-title {
            color: #ffffff;
            font-weight: 800;
            font-size: 1.35rem;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .stat-desc {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
            line-height: 1.5;
            margin-bottom: 0;
        }

        .stat-count-label {
            color: var(--glow-color);
            font-size: 0.7rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 5px;
            display: block;
        }
    </style>
@endsection

@section('content')
    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">
            <div class="content-inner">
                <!-- Content area -->
                <div class="content">
                    <!-- FIX: LIVEWIRE MEMBUTUHKAN VARIABEL $SLOT AGAR ISINYA MUNCUL -->
                    @if (isset($slot))
                        {{ $slot }}
                    @endif
                    @yield('content')
                </div>
                <!-- /content area -->
            </div>
        </div>
    </div>
@endsection



