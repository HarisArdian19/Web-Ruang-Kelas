<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penjadwalan & Bebas Bentrok Ruang GKU 3 - Telkom University</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between font-sans antialiased selection:bg-red-600 selection:text-white">

    <!-- Navbar -->
    <header class="border-b border-slate-800 bg-slate-900/90 backdrop-blur sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-700 flex items-center justify-center text-white text-xl shadow-lg shadow-red-700/30">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h1 class="text-base font-extrabold tracking-tight text-white flex items-center gap-2">
                        GKU 3 Room Management System
                        <span class="bg-red-600/20 text-red-400 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-500/30">v2.0</span>
                    </h1>
                    <p class="text-xs text-slate-400">Telkom University Bandung • 10 Lantai & 200 Ruangan</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Zero-Conflict Engine Aktif
                </span>
            </div>
        </div>
    </header>

    <!-- Hero Content -->
    <main class="max-w-6xl mx-auto px-6 py-12 flex-1 flex flex-col justify-center">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-3">
                Pilih Portal Akses Sistem
            </h2>
            <p class="text-slate-400 text-sm leading-relaxed">
                Sistem penjadwalan dinamis terpadu dengan deteksi bentrok otomatis, pemesanan multi-jam (rentang jam mulai & selesai), pembatalan kelas, dan notifikasi pengingat otomatis H-1.
            </p>
        </div>

        <!-- Two Dedicated Portal Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto w-full">
            
            <!-- Card 1: Portal Dosen -->
            <a href="{{ route('portal.dosen') }}" class="group relative bg-slate-800/80 hover:bg-slate-800 border border-slate-700 hover:border-red-500/60 rounded-3xl p-7 transition-all duration-300 shadow-xl hover:shadow-2xl hover:shadow-red-900/20 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-red-600/20 border border-red-500/30 text-red-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <span class="text-xs font-bold text-red-400 bg-red-500/10 px-3 py-1 rounded-full border border-red-500/20">
                            Akses Pengajar
                        </span>
                    </div>

                    <h3 class="text-xl font-bold text-white mb-2 group-hover:text-red-400 transition-colors">
                        Portal Dosen
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-6">
                        Khusus dosen pengajar (Dr. Haris Ardian - CSA). Ajukan perpindahan jadwal dengan durasi rentang jam, batalkan kelas yang ditiadakan, dan cek kotak masuk pengingat H-1.
                    </p>

                    <div class="space-y-2 border-t border-slate-700/60 pt-4 text-xs text-slate-300">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-clock text-red-400 text-xs"></i>
                            <span>Pemesanan Rentang Waktu (Jam Mulai s.d. Selesai)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-ban text-red-400 text-xs"></i>
                            <span>Fitur Pembatalan Jadwal (Bebaskan Ruangan)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-red-400 text-xs"></i>
                            <span>Notifikasi Pengingat Kuliah H-1 ke Email</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-4 flex items-center justify-between text-xs font-bold text-red-400">
                    <span>Masuk ke Portal Dosen</span>
                    <i class="fa-solid fa-arrow-right transform group-hover:translate-x-1.5 transition-transform"></i>
                </div>
            </a>

            <!-- Card 2: Portal Admin Gedung -->
            <a href="{{ route('portal.admin') }}" class="group relative bg-slate-800/80 hover:bg-slate-800 border border-slate-700 hover:border-blue-500/60 rounded-3xl p-7 transition-all duration-300 shadow-xl hover:shadow-2xl hover:shadow-blue-900/20 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-14 h-14 rounded-2xl bg-blue-600/20 border border-blue-500/30 text-blue-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-building-shield"></i>
                        </div>
                        <span class="text-xs font-bold text-blue-400 bg-blue-500/10 px-3 py-1 rounded-full border border-blue-500/20 flex items-center gap-1.5">
                            @if($pendingCount > 0)
                                <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                                {{ $pendingCount }} Menunggu
                            @else
                                Hak Akses Admin
                            @endif
                        </span>
                    </div>

                    <h3 class="text-xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors">
                        Portal Admin Gedung GKU 3
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-6">
                        Khusus Bagian Logistik & Urusan Ruangan Kelas. Verifikasi pengajuan reschedule (Setujui/Alihkan/Tolak), kelola ruangan rusak (maintenance), dan pantau kelas yang dibatalkan.
                    </p>

                    <div class="space-y-2 border-t border-slate-700/60 pt-4 text-xs text-slate-300">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-blue-400 text-xs"></i>
                            <span>Verifikasi Antrean Reschedule (1-Klik Approve)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-shuffle text-blue-400 text-xs"></i>
                            <span>Otoritas Alihkan Ruangan Pengganti (Reroute)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-wrench text-blue-400 text-xs"></i>
                            <span>Kunci Ruangan Maintenance / AC & LCD Rusak</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-4 flex items-center justify-between text-xs font-bold text-blue-400">
                    <span>Masuk ke Portal Admin</span>
                    <i class="fa-solid fa-arrow-right transform group-hover:translate-x-1.5 transition-transform"></i>
                </div>
            </a>

        </div>

        <!-- Live Metrics / Stats Summary -->
        <div class="mt-12 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-4xl mx-auto w-full">
            <div class="bg-slate-800/40 border border-slate-800 p-4 rounded-2xl text-center">
                <span class="block text-2xl font-extrabold text-white">200</span>
                <span class="text-[11px] text-slate-400 uppercase font-semibold">Total Ruang (10 Lantai)</span>
            </div>
            <div class="bg-slate-800/40 border border-slate-800 p-4 rounded-2xl text-center">
                <span class="block text-2xl font-extrabold text-emerald-400">{{ $activeRooms }}</span>
                <span class="text-[11px] text-slate-400 uppercase font-semibold">Ruangan Aktif</span>
            </div>
            <div class="bg-slate-800/40 border border-slate-800 p-4 rounded-2xl text-center">
                <span class="block text-2xl font-extrabold text-amber-400">{{ $pendingCount }}</span>
                <span class="text-[11px] text-slate-400 uppercase font-semibold">Pengajuan Reschedule</span>
            </div>
            <div class="bg-slate-800/40 border border-slate-800 p-4 rounded-2xl text-center">
                <span class="block text-2xl font-extrabold text-red-400">{{ $maintenanceRooms }}</span>
                <span class="text-[11px] text-slate-400 uppercase font-semibold">Ruang Maintenance</span>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800 py-4 text-center text-xs text-slate-500">
        &copy; 2026 GKU 3 Room Management System • Telkom University Bandung.
    </footer>

</body>
</html>
