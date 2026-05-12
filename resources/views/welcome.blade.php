<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MealManage Pro — Smart Meal Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }

        .gradient-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 30%, #0f172a 60%, #0c1a35 100%);
        }

        .hero-glow {
            background: radial-gradient(ellipse 80% 50% at 50% -10%, rgba(99,102,241,0.35), transparent);
        }

        .glass {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .glass-card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(99,102,241,0.4);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(99,102,241,0.15);
        }

        .btn-glow {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            box-shadow: 0 0 30px rgba(99,102,241,0.5);
            transition: all 0.3s ease;
        }
        .btn-glow:hover {
            box-shadow: 0 0 50px rgba(99,102,241,0.8);
            transform: translateY(-2px) scale(1.02);
        }

        .btn-outline-glow {
            border: 1px solid rgba(99,102,241,0.5);
            color: #a5b4fc;
            transition: all 0.3s ease;
        }
        .btn-outline-glow:hover {
            background: rgba(99,102,241,0.15);
            border-color: #6366f1;
            color: #fff;
            box-shadow: 0 0 20px rgba(99,102,241,0.3);
        }

        .stat-number {
            background: linear-gradient(135deg, #a5b4fc, #c4b5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .feature-icon {
            background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(139,92,246,0.2));
            border: 1px solid rgba(99,102,241,0.3);
        }

        .blob-1 { animation: blob 8s infinite; }
        .blob-2 { animation: blob 10s infinite 2s; }
        .blob-3 { animation: blob 12s infinite 4s; }

        @keyframes blob {
            0%, 100% { transform: translate(0,0) scale(1); }
            33% { transform: translate(30px,-20px) scale(1.1); }
            66% { transform: translate(-20px,20px) scale(0.9); }
        }

        .float-badge { animation: floatUp 3s ease-in-out infinite; }
        .float-badge-2 { animation: floatUp 3s ease-in-out infinite 1.5s; }

        @keyframes floatUp {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        /* Modal */
        .modal-backdrop {
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
        }
        .modal-enter { animation: modalIn 0.3s ease-out; }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        .nav-link {
            color: rgba(255,255,255,0.6);
            transition: color 0.2s;
        }
        .nav-link:hover { color: #fff; }

        .section-badge {
            background: rgba(99,102,241,0.15);
            border: 1px solid rgba(99,102,241,0.3);
            color: #a5b4fc;
        }

        .grid-bg {
            background-image: linear-gradient(rgba(99,102,241,0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(99,102,241,0.05) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .meal-card-preview {
            background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(139,92,246,0.05));
            border: 1px solid rgba(99,102,241,0.2);
        }

        /* Scroll fade-in */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen text-white overflow-x-hidden">

    <!-- Grid background -->
    <div class="fixed inset-0 grid-bg opacity-50 pointer-events-none"></div>

    <!-- Blobs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="blob-1 absolute top-[-200px] right-[-100px] w-[600px] h-[600px] rounded-full" style="background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%)"></div>
        <div class="blob-2 absolute bottom-[-100px] left-[-100px] w-[500px] h-[500px] rounded-full" style="background: radial-gradient(circle, rgba(139,92,246,0.12) 0%, transparent 70%)"></div>
        <div class="blob-3 absolute top-[40%] left-[40%] w-[400px] h-[400px] rounded-full" style="background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, transparent 70%)"></div>
    </div>

    <!-- Navbar -->
    <nav class="relative z-50 glass border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                    🍽
                </div>
                <div>
                    <span class="font-bold text-white text-lg">MealManage</span>
                    <span class="font-bold text-lg" style="background: linear-gradient(135deg, #6366f1, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Pro</span>
                </div>
            </div>

            <!-- Nav links -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="nav-link text-sm font-medium">Features</a>
                <a href="#stats" class="nav-link text-sm font-medium">Stats</a>
                <a href="#how-it-works" class="nav-link text-sm font-medium">How it works</a>
            </div>

            <!-- CTA -->
            <div class="flex items-center gap-3">
                <button onclick="openLoginModal()" class="btn-outline-glow px-5 py-2.5 rounded-xl text-sm font-semibold">
                    Sign In
                </button>
                <button onclick="openRegisterModal()" class="btn-glow px-5 py-2.5 rounded-xl text-sm font-semibold text-white">
                    Sign Up →
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="relative z-10 pt-28 pb-24 px-6">
        <div class="hero-glow absolute inset-0 pointer-events-none"></div>

        <div class="max-w-5xl mx-auto text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 section-badge px-4 py-2 rounded-full text-sm font-medium mb-8">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                Smart Meal Management for Teams
            </div>

            <!-- Heading -->
            <h1 class="text-5xl md:text-7xl font-black leading-tight mb-6">
                Manage Every
                <span class="block" style="background: linear-gradient(135deg, #818cf8, #c084fc, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Meal. Every Cost.
                </span>
                Every Member.
            </h1>

            <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                A complete meal management platform for organizations. Track meals, calculate costs, manage bazar expenses, and generate reports — all in one place.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                <button onclick="openLoginModal()"
                    class="btn-glow px-8 py-4 rounded-2xl text-base font-bold text-white flex items-center gap-3 w-full sm:w-auto justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Login to Dashboard
                </button>
                <a href="#features"
                    class="btn-outline-glow px-8 py-4 rounded-2xl text-base font-semibold flex items-center gap-3 w-full sm:w-auto justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Explore Features
                </a>
            </div>

            <!-- ─── Today's Meal Entries ───────────────────────────────────────────── -->
            <section class="relative z-10 py-20 px-6">
                <div class="max-w-5xl mx-auto text-left">

                    <!-- Header -->
                    <div class="text-center mb-8 fade-in-up">
                        <div class="inline-flex items-center gap-2 section-badge px-4 py-2 rounded-full text-sm font-medium mb-4">
                            <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                            Live Today
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black mb-3">Today's Meal Entries</h2>
                        <p class="text-slate-400">Real-time view of who's eating what today — <strong class="text-white">{{ $todayMeals->flatten()->count() }}</strong> entries so far.</p>
                    </div>

                    @if($todayMeals->isEmpty())
                        <div class="glass rounded-2xl p-10 text-center fade-in-up">
                            <div class="text-5xl mb-3">🍽</div>
                            <p class="text-slate-400">No meal entries recorded for today yet.</p>
                        </div>
                    @else
                    @php
                        $allEntries  = $todayMeals->flatten();
                        $userMeals   = $allEntries->groupBy('user_id');
                        $typeMap     = ['breakfast' => ['🌅','Breakfast'], 'lunch' => ['☀️','Lunch'], 'dinner' => ['🌙','Dinner'], 'custom' => ['⭐','Custom']];
                        $activeTypes = collect(array_keys($typeMap))->filter(fn($t) => $todayMeals->has($t));
                    @endphp

                    <div class="fade-in-up space-y-5">
                        <!-- Summary pills -->
                        <div class="flex flex-wrap gap-3 justify-center mb-2">
                            @foreach($activeTypes as $type)
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium" style="background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25)">
                                <span>{{ $typeMap[$type][0] }}</span>
                                <span class="font-semibold text-white">{{ $typeMap[$type][1] }}</span>
                                <span class="text-xs text-slate-400">{{ $todayMeals[$type]->count() }} members · {{ number_format($todayMeals[$type]->sum('quantity'), 1) }} meals</span>
                            </div>
                            @endforeach
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium" style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25)">
                                <span>👤</span>
                                <span class="font-semibold text-white">{{ $userMeals->count() }} Members</span>
                                <span class="text-xs text-slate-400">· {{ number_format($allEntries->sum('quantity'), 1) }} total meals</span>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="glass rounded-2xl overflow-hidden" style="box-shadow: 0 20px 40px rgba(0,0,0,0.3)">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr style="background: rgba(99,102,241,0.12); border-bottom: 1px solid rgba(255,255,255,0.08)">
                                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider w-10">#</th>
                                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Member</th>
                                            @foreach($activeTypes as $type)
                                            <th class="px-5 py-3.5 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                                {{ $typeMap[$type][0] }} {{ $typeMap[$type][1] }}
                                            </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userMeals as $userId => $entries)
                                        @php $user = $entries->first()->user; @endphp
                                        <tr class="border-b border-white/5 transition-colors" style="hover: background: rgba(255,255,255,0.03)">
                                            <td class="px-5 py-3.5 text-slate-500 text-xs font-mono">{{ $loop->iteration }}</td>
                                            <td class="px-5 py-3.5">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0" style="ring: 2px solid rgba(99,102,241,0.4)" alt="">
                                                    <span class="text-white font-semibold text-sm">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            @foreach($activeTypes as $type)
                                            @php $typeEntry = $entries->firstWhere('meal_type', $type); @endphp
                                            <td class="px-5 py-3.5 text-center">
                                                @if($typeEntry)
                                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold" style="background: rgba(99,102,241,0.2); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.3)">
                                                        {{ number_format($typeEntry->quantity, 1) }}
                                                    </span>
                                                @else
                                                    <span class="text-slate-700">—</span>
                                                @endif
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="background: rgba(99,102,241,0.08); border-top: 1px solid rgba(255,255,255,0.08)">
                                            <td colspan="2" class="px-5 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Grand Total</td>
                                            @foreach($activeTypes as $type)
                                            <td class="px-5 py-3 text-center text-xs font-bold" style="color: #a5b4fc">
                                                {{ number_format($todayMeals[$type]->sum('quantity'), 1) }}
                                            </td>
                                            @endforeach
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </section>

            <!-- App Preview Card -->
            <div class="relative max-w-4xl mx-auto">
                <!-- Floating badges -->
                <div class="float-badge absolute -top-4 -left-4 z-20 glass px-4 py-2 rounded-xl flex items-center gap-2 text-sm font-semibold shadow-xl">
                    <span class="text-emerald-400 text-lg">✓</span>
                    <span class="text-slate-200">Today's meals: <strong class="text-white">{{ number_format($todayTotalQty, 1) }}</strong></span>
                </div>
                <div class="float-badge-2 absolute -top-4 -right-4 z-20 glass px-4 py-2 rounded-xl flex items-center gap-2 text-sm font-semibold shadow-xl">
                    <span class="text-amber-400 text-lg">৳</span>
                    <span class="text-slate-200">Monthly cost: <strong class="text-white">৳{{ number_format($monthBazarTotal, 0) }}</strong></span>
                </div>

                <!-- Dashboard Preview -->
                <div class="glass rounded-3xl overflow-hidden shadow-2xl" style="box-shadow: 0 40px 80px rgba(0,0,0,0.5), 0 0 60px rgba(99,102,241,0.2);">
                    <!-- Fake top bar -->
                    <div class="flex items-center gap-2 px-5 py-3 border-b border-white/5" style="background: rgba(255,255,255,0.03)">
                        <div class="w-3 h-3 rounded-full bg-red-500/70"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500/70"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/70"></div>
                        <div class="flex-1 mx-4">
                            <div class="glass rounded-md px-3 py-1 text-xs text-slate-500 text-center">mealmanage.pro/dashboard</div>
                        </div>
                    </div>

                    <!-- Fake dashboard layout -->
                    <div class="flex h-64">
                        <!-- Fake sidebar -->
                        <div class="w-44 border-r border-white/5 p-3 space-y-1 hidden sm:block" style="background: rgba(0,0,0,0.3)">
                            <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs" style="background: rgba(99,102,241,0.3); color: #a5b4fc">
                                <div class="w-3 h-3 rounded bg-indigo-400/60"></div>
                                <span>Dashboard</span>
                            </div>
                            @foreach(['Meal Entries','Bazar','Reports','Users','Settings'] as $item)
                            <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-slate-500">
                                <div class="w-3 h-3 rounded bg-slate-700"></div>
                                <span>{{ $item }}</span>
                            </div>
                            @endforeach
                        </div>

                        <!-- Fake main content -->
                        <div class="flex-1 p-4">
                            <!-- Stat cards row -->
                            <div class="grid grid-cols-4 gap-2 mb-3">
                                    @foreach([
                                    ['Total Meals', number_format($totalMeals, 0)],
                                    ['Today', number_format($todayTotalQty, 1)],
                                    ['Month Bazar', '৳'.number_format($monthBazarTotal, 0)],
                                    ['Members', $activeMembers],
                                ] as [$label, $val])
                                <div class="rounded-xl p-2 text-center" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06)">
                                    <div class="text-sm font-bold text-white">{{ $val }}</div>
                                    <div class="text-xs text-slate-500">{{ $label }}</div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Chart placeholder -->
                            <div class="grid grid-cols-3 gap-2">
                                <div class="col-span-2 rounded-xl p-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); height: 120px">
                                    <div class="text-xs text-slate-500 mb-2">Monthly Trend</div>
                                    <div class="flex items-end gap-1 h-16">
                                        @foreach($trendData as $val)
                                        <div class="flex-1 rounded-t" style="height: {{ max(4, round($val / $trendMax * 100)) }}%; background: linear-gradient(to top, rgba(99,102,241,0.8), rgba(139,92,246,0.4))"></div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="rounded-xl p-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); height: 120px">
                                    <div class="text-xs text-slate-500 mb-2">Today's Meals</div>
                                    <div class="flex items-center justify-center h-16">
                                        <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background: conic-gradient(#6366f1 0% 55%, #8b5cf6 55% 80%, #3b82f6 80% 100%); padding: 4px">
                                            <div class="w-full h-full rounded-full flex items-center justify-center" style="background: #0f172a">
                                                <span class="text-xs font-bold">{{ number_format($todayTotalQty, 0) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="relative z-10 py-20 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="glass rounded-3xl p-10">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    @foreach([['1,000+','Meals Tracked','per month'],['100%','Automated','cost calculation'],['Real-time','Reports','PDF & Excel'],['Role-based','Access','3 user levels']] as [$num,$label,$sub])
                    <div class="fade-in-up">
                        <div class="stat-number text-3xl md:text-4xl font-black mb-1">{{ $num }}</div>
                        <div class="text-white font-semibold text-sm">{{ $label }}</div>
                        <div class="text-slate-500 text-xs mt-1">{{ $sub }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="relative z-10 py-24 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16 fade-in-up">
                <div class="inline-flex items-center gap-2 section-badge px-4 py-2 rounded-full text-sm font-medium mb-4">
                    Everything you need
                </div>
                <h2 class="text-4xl md:text-5xl font-black mb-4">Powerful Features</h2>
                <p class="text-slate-400 text-lg max-w-xl mx-auto">Built for teams who want full control over meal management, costs, and reporting.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['🍱','Meal Entry Management','Log breakfast, lunch, dinner or custom meals. Support bulk entries, half-meals, and guest meals with approval workflow.','indigo'],
                    ['🛒','Bazar Management','Track grocery expenses with categories, vendors, receipt images and verification. Get category-wise expense breakdowns.','purple'],
                    ['📊','Cost Calculation','Auto-calculate per-meal cost from total bazar expense. Monthly finalization with per-member cost and balance sheet.','blue'],
                    ['📄','PDF & Excel Reports','Generate professional monthly reports and user-wise annual reports. Export to PDF or Excel in one click.','emerald'],
                    ['👥','User Management','Role-based access (Super Admin, Manager, Staff). Track last login, meal status, and manage permissions per user.','amber'],
                    ['🔔','Smart Notifications','Daily meal reminders, monthly report notifications via email and in-app. Full activity audit log.','rose'],
                ] as [$icon, $title, $desc, $color])
                <div class="glass-card rounded-2xl p-6 fade-in-up">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center text-2xl mb-4">{{ $icon }}</div>
                    <h3 class="text-white font-bold text-lg mb-2">{{ $title }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section id="how-it-works" class="relative z-10 py-24 px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16 fade-in-up">
                <div class="inline-flex items-center gap-2 section-badge px-4 py-2 rounded-full text-sm font-medium mb-4">
                    Simple workflow
                </div>
                <h2 class="text-4xl md:text-5xl font-black mb-4">How It Works</h2>
            </div>

            <div class="space-y-6">
                @foreach([
                    ['01','Staff logs meals','Members log their daily breakfast, lunch, and dinner from their dashboard. Managers can bulk-enter for the whole team.'],
                    ['02','Manager records bazar','Every grocery purchase is recorded with amount, category, vendor, and receipt. Total monthly expense is tracked.'],
                    ['03','System calculates costs','At month-end, the system divides total bazar cost by total meals to get per-meal cost for each member.'],
                    ['04','Reports generated','Beautiful PDF and Excel reports are generated and emailed to all members with their personal cost summary.'],
                ] as [$num, $title, $desc])
                <div class="glass-card rounded-2xl p-6 flex items-start gap-5 fade-in-up">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center font-black text-lg" style="background: linear-gradient(135deg, rgba(99,102,241,0.3), rgba(139,92,246,0.2)); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.3)">
                        {{ $num }}
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-1">{{ $title }}</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">{{ $desc }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <section class="relative z-10 py-24 px-6">
        <div class="max-w-3xl mx-auto text-center fade-in-up">
            <div class="glass rounded-3xl p-12" style="box-shadow: 0 0 60px rgba(99,102,241,0.15)">
                <div class="text-5xl mb-4">🍽</div>
                <h2 class="text-4xl font-black mb-4">Ready to get started?</h2>
                <p class="text-slate-400 text-lg mb-8">Sign in to your account and take control of your organization's meal management.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button onclick="openLoginModal()"
                        class="btn-glow px-10 py-4 rounded-2xl text-base font-bold text-white inline-flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Login Now
                    </button>
                    <button onclick="openRegisterModal()"
                        class="btn-outline-glow px-10 py-4 rounded-2xl text-base font-bold inline-flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Create Account
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative z-10 py-8 px-6 border-t border-white/5 text-center text-slate-500 text-sm">
        © {{ date('Y') }} MealManage Pro. All rights reserved.
    </footer>

    <!-- ─── REGISTER MODAL ─────────────────────────────────────────────────────── -->
    <div id="registerModal"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 hidden"
        onclick="handleRegisterBackdropClick(event)">

        <!-- Backdrop -->
        <div class="absolute inset-0 modal-backdrop"></div>

        <!-- Modal card -->
        <div id="registerModalCard"
            class="relative glass rounded-3xl w-full max-w-lg p-8 modal-enter overflow-y-auto"
            style="max-height: 90vh; box-shadow: 0 40px 80px rgba(0,0,0,0.6), 0 0 60px rgba(99,102,241,0.2)">

            <!-- Close -->
            <button onclick="closeRegisterModal()"
                class="absolute top-5 right-5 w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Header -->
            <div class="text-center mb-7">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4" style="background: linear-gradient(135deg, #6366f1, #8b5cf6)">✨</div>
                <h2 class="text-2xl font-black text-white">Create Account</h2>
                <p class="text-slate-400 text-sm mt-1">Join MealManage Pro today</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Full Name</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="John Doe"
                            class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="you@example.com"
                            class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4" x-data="{ show: false }">
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password" required
                            placeholder="Min. 8 characters"
                            class="w-full pl-10 pr-12 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                        <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6" x-data="{ show: false }">
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                            placeholder="Repeat password"
                            class="w-full pl-10 pr-12 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                        <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="btn-glow w-full py-3.5 rounded-xl text-base font-bold text-white flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Create Account
                </button>
            </form>

            <!-- Switch to Login -->
            <p class="text-center text-sm text-slate-500 mt-5">
                Already have an account?
                <button type="button" onclick="closeRegisterModal(); openLoginModal()" class="text-indigo-400 hover:text-indigo-300 font-semibold transition ml-1">Sign in</button>
            </p>
        </div>
    </div>

    <!-- ─── LOGIN MODAL ──────────────────────────────────────────────────────── -->
    <div id="loginModal"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 hidden"
        onclick="handleModalBackdropClick(event)">

        <!-- Backdrop -->
        <div class="absolute inset-0 modal-backdrop"></div>

        <!-- Modal card -->
        <div id="modalCard"
            class="relative glass rounded-3xl w-full max-w-md p-8 modal-enter"
            style="box-shadow: 0 40px 80px rgba(0,0,0,0.6), 0 0 60px rgba(99,102,241,0.2)">

            <!-- Close -->
            <button onclick="closeLoginModal()"
                class="absolute top-5 right-5 w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4" style="background: linear-gradient(135deg, #6366f1, #8b5cf6)">🍽</div>
                <h2 class="text-2xl font-black text-white">Welcome back</h2>
                <p class="text-slate-400 text-sm mt-1">Sign in to your account to continue</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="admin@example.com"
                            class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-6" x-data="{ show: false }">
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-12 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                        <button type="button" @click="show = !show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Remember + Forgot -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-500/20">
                        <span class="text-sm text-slate-400">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-400 hover:text-indigo-300 transition">Forgot password?</a>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="btn-glow w-full py-3.5 rounded-xl text-base font-bold text-white flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Sign In
                </button>
            </form>

            <!-- Demo credentials hint -->
            <div class="mt-5 p-3 rounded-xl text-xs text-center" style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2)">
                <span class="text-slate-400">Demo: </span>
                <span class="text-indigo-300 font-mono">admin@example.com</span>
                <span class="text-slate-500"> / </span>
                <span class="text-indigo-300 font-mono">Password1</span>
            </div>

            <!-- Switch to Register -->
            <p class="text-center text-sm text-slate-500 mt-4">
                Don't have an account?
                <button type="button" onclick="closeLoginModal(); openRegisterModal()" class="text-indigo-400 hover:text-indigo-300 font-semibold transition ml-1">Create account</button>
            </p>
        </div>
    </div>

    <script>
        // Modal functions
        function openLoginModal() {
            const modal = document.getElementById('loginModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLoginModal() {
            const modal = document.getElementById('loginModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function handleModalBackdropClick(e) {
            if (e.target === document.getElementById('loginModal')) {
                closeLoginModal();
            }
        }

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { closeLoginModal(); closeRegisterModal(); }
        });

        // Register modal functions
        function openRegisterModal() {
            document.getElementById('registerModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeRegisterModal() {
            document.getElementById('registerModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
        function handleRegisterBackdropClick(e) {
            if (e.target === document.getElementById('registerModal')) {
                closeRegisterModal();
            }
        }

        // Scroll fade-in animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));
    </script>
</body>
</html>
