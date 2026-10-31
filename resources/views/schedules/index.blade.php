<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Penjadwalan & Bebas Bentrok Ruang GKU 3 - Telkom University</title>
    <!-- Tailwind CSS CDN for instant styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Matrix custom scrolling */
        .matrix-container {
            max-height: calc(100vh - 280px);
            min-height: 540px;
            overflow: auto;
        }
        .sticky-col {
            position: sticky;
            left: 0;
            z-index: 20;
            background-color: #f8fafc;
        }
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 30;
        }
        .sticky-corner {
            position: sticky;
            top: 0;
            left: 0;
            z-index: 40;
            background-color: #e2e8f0;
        }
        /* Custom scrollbar */
        .matrix-container::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        .matrix-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
        }
        .matrix-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased">

    <!-- Top Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-[1700px] mx-auto px-4 py-2.5 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-700 text-white flex items-center justify-center font-bold text-xl shadow-md">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-lg font-bold text-slate-900 leading-tight">GKU 3 Room Management System</h1>
                        <span class="bg-red-100 text-red-800 text-[11px] font-semibold px-2 py-0.5 rounded border border-red-200">
                            Telkom University
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">Sistem Penjadwalan Dinamis & Deteksi Nol Bentrok (Zero-Conflict Engine)</p>
                </div>
            </div>

            <!-- Role Switcher & User Profile -->
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <span class="text-xs text-slate-400 block font-medium">Beralih Hak Akses:</span>
                    <span id="roleLabel" class="text-xs font-bold text-red-700">Dosen (Haris Ardian - CSA)</span>
                </div>
                <div class="bg-slate-200 p-1 rounded-xl flex items-center gap-1 border border-slate-300">
                    <button id="btnRoleDosen" onclick="setRole('dosen')" class="px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-all bg-white text-slate-900 shadow-sm">
                        <i class="fa-solid fa-user-tie text-red-600"></i> Mode Dosen
                    </button>
                    <button id="btnRoleAdmin" onclick="setRole('admin')" class="px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-all text-slate-600 hover:text-slate-900">
                        <i class="fa-solid fa-shield-halved text-blue-600"></i> Mode Admin Gedung
                        <span id="badgePendingCount" class="bg-red-600 text-white text-[10px] px-1.5 py-0.2 rounded-full font-bold ml-0.5 {{ count($pendingRequests) > 0 ? '' : 'hidden' }}">
                            {{ count($pendingRequests) }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-[1700px] mx-auto px-4 py-4 space-y-3">

        <!-- Role Notification & Action Banners -->
        <!-- 1. Admin Action Banner -->
        <div id="adminBanner" class="hidden bg-gradient-to-r from-blue-900 to-indigo-900 text-white rounded-xl p-3.5 shadow-md border border-blue-800 transition-all">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-700/60 flex items-center justify-center text-amber-300 text-lg">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm tracking-wide">Pusat Antrean Persetujuan Reschedule Gedung GKU 3</h3>
                            <span class="bg-amber-400 text-slate-900 text-xs font-extrabold px-2 py-0.5 rounded-full">
                                {{ count($pendingRequests) }} Pengajuan Tertunda
                            </span>
                        </div>
                        <p class="text-xs text-blue-200">Admin memiliki wewenang menyetujui, menolak, mengalihkan ruangan (*reroute*), atau mengunci ruangan bermasalah (*maintenance*).</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="openAdminQueueModal()" class="bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold px-3.5 py-1.5 rounded-lg text-xs shadow flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-list-check"></i> Buka Antrean Pengajuan ({{ count($pendingRequests) }})
                    </button>
                    <button onclick="openQuickMaintenanceModal()" class="bg-blue-800 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold border border-blue-600 flex items-center gap-1.5">
                        <i class="fa-solid fa-wrench"></i> Kunci Ruangan (Maintenance)
                    </button>
                </div>
            </div>
        </div>

        <!-- 2. Dosen Info Banner -->
        <div id="dosenBanner" class="bg-white rounded-xl p-3.5 shadow-sm border border-slate-200 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-bold">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-800">Panel Pengajuan Perubahan Jadwal Kuliah</h3>
                    <p class="text-xs text-slate-500">Klik kotak <span class="bg-slate-100 border border-slate-300 text-slate-700 px-1.5 py-0.5 rounded text-[11px] font-semibold">+ Ajukan</span> pada jam dan ruang kosong yang Anda inginkan untuk mengajukan reschedule secara instan.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="openRescheduleModal()" class="bg-red-700 hover:bg-red-800 text-white font-semibold px-3.5 py-1.5 rounded-lg text-xs shadow-sm flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-clock-rotate-left"></i> Ajukan Reschedule Kelas Saya
                </button>
                <button onclick="openMyScheduleModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-3 py-1.5 rounded-lg text-xs border border-slate-300 flex items-center gap-1.5">
                    <i class="fa-solid fa-book-open"></i> Jadwal Mengajar Saya (CSA)
                </button>
            </div>
        </div>

        <!-- Controls Bar: Day Selector & Floor Tabs -->
        <div class="bg-white rounded-xl p-3 shadow-sm border border-slate-200 space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Day Select -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1">
                        <i class="fa-regular fa-calendar text-red-600"></i> Hari:
                    </span>
                    <div class="flex flex-wrap gap-1" id="dayButtons">
                        @foreach($days as $day)
                            <button onclick="changeDay('{{ $day }}')" 
                                class="day-btn px-3 py-1 rounded-lg text-xs font-semibold transition-all {{ $day === $defaultDay ? 'bg-red-700 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}" 
                                data-day="{{ $day }}">
                                {{ $day }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Legend / Status Guide -->
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-emerald-100 border border-emerald-500 inline-block"></span>
                        <span class="text-slate-600 font-medium">Terpakai (Kuliah)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-amber-100 border border-amber-400 inline-block"></span>
                        <span class="text-slate-600 font-medium">Pending Verifikasi</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-white border border-slate-300 inline-block"></span>
                        <span class="text-slate-600 font-medium">Ruang Kosong (Bebas)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-slate-700 border border-slate-900 inline-block"></span>
                        <span class="text-slate-600 font-medium">Maintenance / Rusak</span>
                    </div>
                </div>
            </div>

            <!-- Floor Tabs (Lantai 1 sampai Lantai 10) -->
            <div class="pt-2 border-t border-slate-100 flex items-center gap-2">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1 mr-1">
                    <i class="fa-solid fa-stairs text-slate-500"></i> Lantai:
                </span>
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 w-full" id="floorTabs">
                    @for($f = 1; $f <= 10; $f++)
                        <button onclick="changeFloor({{ $f }})" 
                            class="floor-tab px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $f === $defaultFloor ? 'bg-slate-900 text-white shadow-sm ring-2 ring-slate-800' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}" 
                            data-floor="{{ $f }}">
                            LANTAI {{ $f }}
                        </button>
                    @endfor
                </div>
            </div>
        </div>

        <!-- The Classroom Matrix Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative">
            <!-- Loading Indicator Overlay -->
            <div id="loadingOverlay" class="absolute inset-0 bg-white/70 backdrop-blur-sm z-50 flex items-center justify-center hidden">
                <div class="flex flex-col items-center gap-2">
                    <i class="fa-solid fa-circle-notch fa-spin text-3xl text-red-600"></i>
                    <span class="text-xs font-bold text-slate-700">Memuat Jadwal Lantai...</span>
                </div>
            </div>

            <div class="matrix-container" id="matrixContainer">
                <table class="w-full border-collapse text-left" id="scheduleTable">
                    <thead>
                        <tr>
                            <!-- Corner cell -->
                            <th class="sticky-corner w-24 min-w-[96px] p-2.5 text-center text-xs font-bold uppercase text-slate-700 border-b border-r border-slate-300">
                                Jam
                            </th>
                            <!-- Room columns GKU3-XX-01 to GKU3-XX-20 will be rendered here -->
                            <template id="roomHeaderTemplate">
                                <!-- injected dynamically -->
                            </template>
                            <tbody id="matrixBody">
                                <!-- Matrix rows injected dynamically -->
                            </tbody>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </main>

    <!-- MODAL 1: Pengajuan Reschedule (Dosen) -->
    <div id="rescheduleModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
            <div class="bg-red-700 px-5 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-plus text-lg"></i>
                    <h3 class="font-bold text-base">Ajukan Perubahan Jadwal Kelas</h3>
                </div>
                <button onclick="closeModal('rescheduleModal')" class="text-white/80 hover:text-white text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="rescheduleForm" onsubmit="submitRescheduleForm(event)" class="p-5 space-y-4 text-xs">
                <!-- Class to reschedule -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Kelas yang Akan Dipindahkan:</label>
                    <select id="modalScheduleSelect" required class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-red-500 focus:outline-none">
                        @foreach($mySchedules as $sch)
                            <option value="{{ $sch->id }}" data-day="{{ $sch->day }}" data-slot="{{ $sch->time_slot }}" data-room="{{ $sch->room->code ?? '' }}">
                                {{ $sch->course_code }} - {{ $sch->course_name }} ({{ $sch->day }} {{ $sch->time_slot }} @ {{ $sch->room->code ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Target details -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Target Hari:</label>
                        <select id="modalTargetDay" onchange="runLiveConflictCheck()" required class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2 text-xs font-medium focus:ring-2 focus:ring-red-500 focus:outline-none">
                            @foreach($days as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Target Jam Mulai:</label>
                        <select id="modalTargetSlot" onchange="runLiveConflictCheck()" required class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2 text-xs font-medium focus:ring-2 focus:ring-red-500 focus:outline-none">
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ $slot }} WIB</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Target Ruangan GKU 3:</label>
                    <select id="modalTargetRoom" onchange="runLiveConflictCheck()" required class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-xs font-medium focus:ring-2 focus:ring-red-500 focus:outline-none">
                        <!-- Populated dynamically -->
                    </select>
                </div>

                <!-- Live Collision Check Indicator Box -->
                <div id="collisionAlertBox" class="p-3 rounded-xl border text-xs flex items-start gap-2.5 bg-slate-50 border-slate-200">
                    <i id="collisionIcon" class="fa-solid fa-spinner fa-spin text-slate-500 mt-0.5"></i>
                    <div id="collisionText" class="flex-1">
                        <span class="font-semibold text-slate-600">Memeriksa ketersediaan ruangan...</span>
                    </div>
                </div>

                <!-- Recommendation box (if conflict) -->
                <div id="recommendationContainer" class="hidden bg-amber-50 border border-amber-200 rounded-xl p-3 space-y-2">
                    <span class="font-bold text-amber-900 block flex items-center gap-1.5">
                        <i class="fa-solid fa-lightbulb text-amber-600"></i> Rekomendasi Ruang Terdekat Bebas Bentrok:
                    </span>
                    <div id="recommendationList" class="space-y-1.5">
                        <!-- Injected -->
                    </div>
                </div>

                <!-- Reason -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Alasan Perubahan Jadwal (Wajib):</label>
                    <textarea id="modalReason" required rows="2" placeholder="Contoh: Maju jadwal sehari karena ada bentrok jadwal sidang/tugas dinas pada jadwal semula..." class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2 text-xs focus:ring-2 focus:ring-red-500 focus:outline-none"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                    <button type="button" onclick="closeModal('rescheduleModal')" class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitReschedule" class="px-4 py-2 rounded-lg bg-red-700 hover:bg-red-800 text-white font-bold shadow-md flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan ke Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: Admin Pending Queue Modal -->
    <div id="adminQueueModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-3xl w-full shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[85vh]">
            <div class="bg-blue-900 px-5 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-lg text-amber-400"></i>
                    <h3 class="font-bold text-base">Antrean Pengajuan Reschedule (Menunggu Verifikasi Admin)</h3>
                </div>
                <button onclick="closeModal('adminQueueModal')" class="text-white/80 hover:text-white text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="p-5 overflow-y-auto space-y-3 flex-1 text-xs">
                @forelse($pendingRequests as $req)
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100/80 transition space-y-3" id="pendingItem-{{ $req->id }}">
                        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 pb-2">
                            <div>
                                <span class="bg-red-100 text-red-800 font-bold px-2 py-0.5 rounded text-[11px] mr-1.5">
                                    {{ $req->course_code }}
                                </span>
                                <strong class="text-slate-900 font-bold text-sm">{{ $req->course_name }}</strong>
                                <span class="text-slate-500 block">Dosen: <strong class="text-slate-700">{{ $req->lecturer_name }} ({{ $req->lecturer_code }})</strong> • Kelas: {{ $req->class_group }}</span>
                            </div>
                            <span class="bg-amber-100 text-amber-800 font-bold px-2 py-1 rounded-full border border-amber-300 flex items-center gap-1">
                                <i class="fa-regular fa-clock"></i> PENDING
                            </span>
                        </div>

                        <!-- Transition Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                            <div class="bg-white p-2.5 rounded-lg border border-slate-200">
                                <span class="text-slate-400 block text-[10px] font-bold uppercase">Jadwal Semula:</span>
                                <div class="font-semibold text-slate-800">
                                    <i class="fa-solid fa-calendar-xmark text-slate-400 mr-1"></i> {{ $req->from_day }}, {{ $req->from_time_slot }} WIB
                                </div>
                                <div class="text-slate-600">
                                    <i class="fa-solid fa-door-open text-slate-400 mr-1"></i> Ruang: {{ $req->fromRoom->code ?? '-' }}
                                </div>
                            </div>
                            <div class="bg-emerald-50 p-2.5 rounded-lg border border-emerald-200">
                                <span class="text-emerald-700 block text-[10px] font-bold uppercase">Target Pengajuan:</span>
                                <div class="font-bold text-emerald-900">
                                    <i class="fa-solid fa-calendar-check text-emerald-600 mr-1"></i> {{ $req->target_day }}, {{ $req->target_time_slot }} WIB
                                </div>
                                <div class="text-emerald-800 font-semibold">
                                    <i class="fa-solid fa-door-open text-emerald-600 mr-1"></i> Ruang: {{ $req->targetRoom->code ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-2 rounded-lg border border-slate-200 text-slate-600">
                            <strong>Alasan Dosen:</strong> {{ $req->reason }}
                        </div>

                        <!-- Actions for Admin -->
                        <div class="flex flex-wrap items-center justify-end gap-2 pt-1">
                            <button onclick="adminReject({{ $req->id }})" class="px-3 py-1.5 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-bold border border-red-300 flex items-center gap-1">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>
                            <button onclick="openRerouteDialog({{ $req->id }}, '{{ $req->target_day }}', '{{ $req->target_time_slot }}', {{ $req->target_room_id }})" class="px-3 py-1.5 rounded-lg bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-bold border border-indigo-300 flex items-center gap-1">
                                <i class="fa-solid fa-shuffle"></i> Alihkan Ruangan (Reroute)
                            </button>
                            <button onclick="adminApprove({{ $req->id }})" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-sm flex items-center gap-1">
                                <i class="fa-solid fa-check"></i> Setujui (Approve)
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        <i class="fa-regular fa-folder-open text-4xl mb-2 block"></i>
                        Tidak ada pengajuan reschedule yang tertunda saat ini.
                    </div>
                @endforelse
            </div>
            
            <div class="bg-slate-100 px-5 py-3 border-t border-slate-200 text-right">
                <button onclick="closeModal('adminQueueModal')" class="px-4 py-2 rounded-lg bg-white border border-slate-300 text-slate-700 font-semibold text-xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL 3: Admin Reroute Dialog -->
    <div id="adminRerouteModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 p-5 space-y-4 text-xs">
            <div class="flex items-center justify-between border-b pb-2">
                <h4 class="font-bold text-sm text-slate-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-shuffle text-indigo-600"></i> Alihkan Jadwal ke Ruang Alternatif
                </h4>
                <button onclick="closeModal('adminRerouteModal')" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <p class="text-slate-600">Pilih ruangan alternatif di GKU 3 yang tersedia pada slot waktu tersebut:</p>
            <input type="hidden" id="rerouteRequestId">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Pilih Ruangan Baru:</label>
                <select id="rerouteRoomSelect" class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 font-medium text-xs">
                    <!-- Populated via script -->
                </select>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t">
                <button onclick="closeModal('adminRerouteModal')" class="px-3 py-1.5 bg-slate-100 rounded-lg text-slate-600 font-semibold">Batal</button>
                <button onclick="submitReroute()" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow">Konfirmasi Alihkan</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toastBox" class="fixed bottom-5 right-5 z-50 transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none">
        <div id="toastContent" class="bg-slate-900 text-white px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 text-xs max-w-md border border-slate-700">
            <i id="toastIcon" class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
            <span id="toastMessage" class="font-medium">Notifikasi</span>
        </div>
    </div>

    <!-- Application Logic Script -->
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let currentRole = 'dosen'; // 'dosen' or 'admin'
        let currentDay = '{{ $defaultDay }}';
        let currentFloor = {{ $defaultFloor }};
        let matrixData = null;
        let allRoomsCache = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadMatrix(currentFloor, currentDay);
            loadAllRoomsForDropdown();
        });

        // 1. Role Switching
        function setRole(role) {
            currentRole = role;
            const btnDosen = document.getElementById('btnRoleDosen');
            const btnAdmin = document.getElementById('btnRoleAdmin');
            const roleLabel = document.getElementById('roleLabel');
            const adminBanner = document.getElementById('adminBanner');
            const dosenBanner = document.getElementById('dosenBanner');

            if (role === 'admin') {
                btnAdmin.className = "px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-all bg-white text-slate-900 shadow-sm";
                btnDosen.className = "px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-all text-slate-600 hover:text-slate-900";
                roleLabel.textContent = "Admin Gedung & Fasilitas GKU 3";
                roleLabel.className = "text-xs font-bold text-blue-700";
                adminBanner.classList.remove('hidden');
                dosenBanner.classList.add('hidden');
                showToast("Beralih ke MODE ADMIN GEDUNG: Akses penuh verifikasi dan kelola ruangan.", "info");
            } else {
                btnDosen.className = "px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-all bg-white text-slate-900 shadow-sm";
                btnAdmin.className = "px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-all text-slate-600 hover:text-slate-900";
                roleLabel.textContent = "Dosen (Haris Ardian - CSA)";
                roleLabel.className = "text-xs font-bold text-red-700";
                adminBanner.classList.add('hidden');
                dosenBanner.classList.remove('hidden');
                showToast("Beralih ke MODE DOSEN (Haris Ardian - CSA).", "info");
            }

            // Re-render table cells to update action buttons
            if (matrixData) {
                renderMatrix(matrixData);
            }
        }

        // 2. Fetch Matrix Data
        async function loadMatrix(floor, day) {
            document.getElementById('loadingOverlay').classList.remove('hidden');
            try {
                const res = await fetch(`/api/matrix?floor=${floor}&day=${encodeURIComponent(day)}`);
                const data = await res.json();
                matrixData = data;
                renderMatrix(data);
            } catch (err) {
                console.error(err);
                showToast("Gagal memuat data matriks ruangan.", "error");
            } finally {
                document.getElementById('loadingOverlay').classList.add('hidden');
            }
        }

        function changeDay(day) {
            currentDay = day;
            document.querySelectorAll('.day-btn').forEach(btn => {
                if (btn.dataset.day === day) {
                    btn.className = "day-btn px-3 py-1 rounded-lg text-xs font-semibold transition-all bg-red-700 text-white shadow-sm";
                } else {
                    btn.className = "day-btn px-3 py-1 rounded-lg text-xs font-semibold transition-all bg-slate-100 text-slate-700 hover:bg-slate-200";
                }
            });
            loadMatrix(currentFloor, currentDay);
        }

        function changeFloor(floor) {
            currentFloor = floor;
            document.querySelectorAll('.floor-tab').forEach(tab => {
                if (parseInt(tab.dataset.floor) === floor) {
                    tab.className = "floor-tab px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap bg-slate-900 text-white shadow-sm ring-2 ring-slate-800";
                } else {
                    tab.className = "floor-tab px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200";
                }
            });
            loadMatrix(currentFloor, currentDay);
        }

        // 3. Render Matrix Grid
        function renderMatrix(data) {
            const table = document.getElementById('scheduleTable');
            const theadTr = table.querySelector('thead tr');
            const tbody = document.getElementById('matrixBody');

            // 3.1 Render Header (Rooms GKU3-XX-01 to GKU3-XX-20)
            theadTr.innerHTML = `
                <th class="sticky-corner w-24 min-w-[96px] p-2.5 text-center text-xs font-bold uppercase text-slate-700 border-b border-r border-slate-300">
                    Jam
                </th>
            `;

            data.rooms.forEach(room => {
                const th = document.createElement('th');
                th.className = `sticky-header min-w-[210px] w-[210px] p-2 text-center border-b border-r border-slate-300 text-xs font-bold ${
                    room.status === 'maintenance' ? 'bg-slate-800 text-amber-300' : 'bg-slate-100 text-slate-800'
                }`;
                
                let maintenanceBadge = '';
                if (room.status === 'maintenance') {
                    maintenanceBadge = `<span class="block text-[10px] font-normal text-amber-200"><i class="fa-solid fa-triangle-exclamation"></i> Maintenance</span>`;
                }

                let adminActionHtml = '';
                if (currentRole === 'admin') {
                    adminActionHtml = `
                        <button onclick="toggleRoomMaintenance(${room.id}, '${room.code}')" title="Ubah status maintenance" class="text-[10px] font-normal underline ml-1 text-blue-500 hover:text-blue-700">
                            [${room.status === 'maintenance' ? 'Aktifkan' : 'Set Rusak'}]
                        </button>
                    `;
                }

                th.innerHTML = `
                    <div class="flex items-center justify-center gap-1">
                        <span>${room.code}</span>
                        ${adminActionHtml}
                    </div>
                    <span class="block text-[10px] font-normal text-slate-500">Kapasitas: ${room.capacity} Kursi</span>
                    ${maintenanceBadge}
                `;
                theadTr.appendChild(th);
            });

            // 3.2 Render Rows (Time slots 06:30 to 18:30)
            tbody.innerHTML = '';

            data.timeSlots.forEach(slot => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50/50 transition-colors";

                // Time Slot column (sticky left)
                const timeTd = document.createElement('td');
                timeTd.className = "sticky-col w-24 min-w-[96px] p-3 text-center text-xs font-bold text-slate-700 border-b border-r border-slate-300 shadow-[1px_0_3px_rgba(0,0,0,0.05)]";
                timeTd.textContent = slot;
                tr.appendChild(timeTd);

                // Room Cells
                data.rooms.forEach(room => {
                    const td = document.createElement('td');
                    td.className = "min-w-[210px] w-[210px] p-2 border-b border-r border-slate-200 align-top transition-all";

                    const cellKey = `${room.id}_${slot}`;
                    const scheduleList = data.schedules[cellKey];
                    const pendingLock = data.pendingTargetSlots[cellKey];

                    if (room.status === 'maintenance') {
                        // Room Maintenance Cell
                        td.className += " bg-slate-200/90 text-slate-500";
                        td.innerHTML = `
                            <div class="p-2 rounded-lg border border-slate-300 bg-white/70 text-center space-y-1">
                                <i class="fa-solid fa-ban text-slate-400 text-sm"></i>
                                <span class="block text-[11px] font-bold text-slate-700">TIDAK TERSEDIA</span>
                                <span class="block text-[10px] text-slate-500 leading-tight">${room.maintenance_reason || 'Perbaikan Fasilitas'}</span>
                            </div>
                        `;
                    } else if (scheduleList && scheduleList.length > 0) {
                        // Confirmed Schedule Cell (Styled like iGracias class card)
                        const sch = scheduleList[0];
                        const isPending = sch.status === 'pending_reschedule';

                        td.className += isPending ? " bg-amber-50" : " bg-emerald-50/40";

                        td.innerHTML = `
                            <div class="p-2.5 rounded-xl border text-left shadow-xs transition hover:shadow-sm ${
                                isPending 
                                    ? 'bg-amber-100/80 border-amber-300 text-amber-900' 
                                    : 'bg-[#dcfce7] border-[#86efac] text-slate-900'
                            }">
                                <div class="flex items-center justify-between gap-1 mb-1">
                                    <span class="font-extrabold text-[11px] tracking-wide text-slate-900">${sch.course_code}</span>
                                    <span class="text-[10px] font-bold px-1.5 py-0.2 rounded ${
                                        isPending ? 'bg-amber-300 text-amber-950' : 'bg-emerald-600 text-white'
                                    }">
                                        ${isPending ? 'Menunggu Admin' : sch.lecturer_code}
                                    </span>
                                </div>
                                <h4 class="font-bold text-[11px] leading-snug line-clamp-2 text-slate-900">${sch.course_name}</h4>
                                <div class="mt-1 pt-1 border-t border-emerald-200/70 text-[10px] text-slate-600 flex items-center justify-between">
                                    <span class="truncate">${sch.lecturer_name}</span>
                                </div>
                                <div class="text-[10px] text-slate-500 font-medium">
                                    Kelas: ${sch.class_group}
                                </div>
                                ${currentRole === 'dosen' && sch.lecturer_code === 'CSA' ? `
                                    <button onclick="directRescheduleFromSlot(${sch.id}, '${sch.course_name}')" class="mt-1.5 w-full bg-red-700 hover:bg-red-800 text-white text-[10px] font-bold py-1 px-2 rounded flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-arrows-split-up-and-left"></i> Pindahkan
                                    </button>
                                ` : ''}
                            </div>
                        `;
                    } else if (pendingLock) {
                        // Pending Lock by Reschedule Request
                        td.className += " bg-amber-50/80";
                        td.innerHTML = `
                            <div class="p-2.5 rounded-xl border border-amber-300 bg-amber-100 text-amber-900 text-left space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-extrabold uppercase text-amber-800">Sedang Diajukan</span>
                                    <i class="fa-solid fa-lock text-amber-700 text-xs"></i>
                                </div>
                                <span class="font-bold text-[11px] block text-slate-900">${pendingLock.course_name}</span>
                                <span class="text-[10px] text-amber-800 block">Dosen: ${pendingLock.lecturer_name}</span>
                                <span class="text-[9px] bg-amber-200 text-amber-900 px-1 rounded block text-center font-semibold">Terkunci Sementara</span>
                            </div>
                        `;
                    } else {
                        // Empty / Available Cell
                        td.innerHTML = `
                            <div class="h-24 rounded-xl border border-dashed border-slate-200 hover:border-slate-400 bg-white hover:bg-slate-50/80 transition-all flex flex-col items-center justify-center p-2 text-center group cursor-pointer"
                                onclick="handleEmptyCellClick(${room.id}, '${room.code}', '${slot}')">
                                <span class="text-[11px] font-semibold text-slate-400 group-hover:text-slate-600 block">Tersedia</span>
                                <span class="mt-1 text-[10px] font-bold px-2 py-0.5 rounded-md ${
                                    currentRole === 'admin' 
                                        ? 'bg-blue-100 text-blue-700 group-hover:bg-blue-600 group-hover:text-white' 
                                        : 'bg-red-50 text-red-700 group-hover:bg-red-700 group-hover:text-white'
                                } transition">
                                    ${currentRole === 'admin' ? '+ Atur Ruang' : '+ Ajukan Pindah'}
                                </span>
                            </div>
                        `;
                    }

                    tr.appendChild(td);
                });

                tbody.appendChild(tr);
            });
        }

        // 4. Click empty cell handling
        function handleEmptyCellClick(roomId, roomCode, timeSlot) {
            if (currentRole === 'dosen') {
                openRescheduleModal(roomId, currentDay, timeSlot);
            } else {
                showToast(`Admin Mode: Slot ${roomCode} pada ${currentDay} ${timeSlot} WIB siap dialokasikan.`, "info");
            }
        }

        // 5. Direct reschedule from my class card
        function directRescheduleFromSlot(scheduleId, courseName) {
            const select = document.getElementById('modalScheduleSelect');
            select.value = scheduleId;
            openRescheduleModal();
            showToast(`Mengajukan pemindahan untuk: ${courseName}`, "info");
        }

        // 6. Reschedule Modal & Live Conflict Engine
        function openRescheduleModal(targetRoomId = null, targetDay = null, targetTimeSlot = null) {
            const modal = document.getElementById('rescheduleModal');
            modal.classList.remove('hidden');

            if (targetDay) document.getElementById('modalTargetDay').value = targetDay;
            if (targetTimeSlot) document.getElementById('modalTargetSlot').value = targetTimeSlot;
            if (targetRoomId) document.getElementById('modalTargetRoom').value = targetRoomId;

            runLiveConflictCheck();
        }

        async function loadAllRoomsForDropdown() {
            try {
                // Pre-generate room list 1 to 10
                const select = document.getElementById('modalTargetRoom');
                const rerouteSelect = document.getElementById('rerouteRoomSelect');
                select.innerHTML = '';
                rerouteSelect.innerHTML = '';

                for (let f = 1; f <= 10; f++) {
                    const optGroup = document.createElement('optgroup');
                    optGroup.label = `Lantai ${f}`;
                    const optGroupReroute = document.createElement('optgroup');
                    optGroupReroute.label = `Lantai ${f}`;

                    for (let r = 1; r <= 20; r++) {
                        const fStr = String(f).padStart(2, '0');
                        const rStr = String(r).padStart(2, '0');
                        const code = `GKU3-${fStr}-${rStr}`;
                        // ID calculation helper assuming sequential seeding
                        const roomId = (f - 1) * 20 + r;

                        const opt = new Option(code, roomId);
                        const optReroute = new Option(code, roomId);
                        optGroup.appendChild(opt);
                        optGroupReroute.appendChild(optReroute);
                    }
                    select.appendChild(optGroup);
                    rerouteSelect.appendChild(optGroupReroute);
                }
            } catch (e) {
                console.error(e);
            }
        }

        // The core real-time collision detector
        async function runLiveConflictCheck() {
            const roomId = document.getElementById('modalTargetRoom').value;
            const day = document.getElementById('modalTargetDay').value;
            const slot = document.getElementById('modalTargetSlot').value;

            const box = document.getElementById('collisionAlertBox');
            const icon = document.getElementById('collisionIcon');
            const text = document.getElementById('collisionText');
            const recContainer = document.getElementById('recommendationContainer');
            const recList = document.getElementById('recommendationList');
            const btnSubmit = document.getElementById('btnSubmitReschedule');

            box.className = "p-3 rounded-xl border text-xs flex items-start gap-2.5 bg-slate-50 border-slate-200";
            icon.className = "fa-solid fa-spinner fa-spin text-slate-500 mt-0.5";
            text.innerHTML = `<span class="font-semibold text-slate-600">Memeriksa ketersediaan ruangan via Engine Nol Bentrok...</span>`;
            recContainer.classList.add('hidden');

            try {
                const res = await fetch('/api/conflict/check', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        target_room_id: roomId,
                        target_day: day,
                        target_time_slot: slot
                    })
                });

                const data = await res.json();

                if (!data.has_conflict) {
                    // 100% Conflict Free!
                    box.className = "p-3 rounded-xl border text-xs flex items-start gap-2.5 bg-emerald-50 border-emerald-300 text-emerald-900";
                    icon.className = "fa-solid fa-circle-check text-emerald-600 text-base mt-0.5";
                    text.innerHTML = `
                        <strong class="block text-emerald-800 font-bold">100% BEBAS BENTROK (TERSEDIA)</strong>
                        <span>${data.message}</span>
                    `;
                    btnSubmit.disabled = false;
                    btnSubmit.className = "px-4 py-2 rounded-lg bg-red-700 hover:bg-red-800 text-white font-bold shadow-md flex items-center gap-1.5 transition";
                } else {
                    // Collision Detected!
                    box.className = "p-3 rounded-xl border text-xs flex items-start gap-2.5 bg-red-50 border-red-300 text-red-900";
                    icon.className = "fa-solid fa-triangle-exclamation text-red-600 text-base mt-0.5";
                    text.innerHTML = `
                        <strong class="block text-red-800 font-bold">PERINGATAN: BENTROK TERDETEKSI!</strong>
                        <span>${data.message}</span>
                    `;
                    btnSubmit.disabled = true;
                    btnSubmit.className = "px-4 py-2 rounded-lg bg-slate-300 text-slate-500 font-bold shadow-none cursor-not-allowed flex items-center gap-1.5";

                    // Show Smart Recommendations
                    if (data.recommendations && (data.recommendations.same_floor.length > 0 || data.recommendations.adjacent_floor.length > 0)) {
                        recContainer.classList.remove('hidden');
                        recList.innerHTML = '';

                        // Priority 1: Same floor
                        data.recommendations.same_floor.forEach(rm => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = "w-full text-left bg-white hover:bg-emerald-50 p-2 rounded-lg border border-amber-300 hover:border-emerald-400 flex items-center justify-between text-slate-800 transition";
                            btn.innerHTML = `
                                <div>
                                    <strong class="text-emerald-700 font-bold">${rm.code}</strong> (Lantai ${rm.floor} - Satu Lantai)
                                    <span class="block text-[10px] text-slate-500">Kapasitas: ${rm.capacity} Kursi</span>
                                </div>
                                <span class="bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded">Pilih Ini</span>
                            `;
                            btn.onclick = () => {
                                document.getElementById('modalTargetRoom').value = rm.id;
                                runLiveConflictCheck();
                            };
                            recList.appendChild(btn);
                        });

                        // Priority 2: Adjacent floors
                        data.recommendations.adjacent_floor.forEach(rm => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = "w-full text-left bg-white hover:bg-slate-50 p-2 rounded-lg border border-slate-200 flex items-center justify-between text-slate-800 transition";
                            btn.innerHTML = `
                                <div>
                                    <strong class="text-slate-800 font-bold">${rm.code}</strong> (Lantai ${rm.floor} - Lantai Dekat)
                                    <span class="block text-[10px] text-slate-500">Kapasitas: ${rm.capacity} Kursi</span>
                                </div>
                                <span class="bg-slate-200 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded">Pilih Ini</span>
                            `;
                            btn.onclick = () => {
                                document.getElementById('modalTargetRoom').value = rm.id;
                                runLiveConflictCheck();
                            };
                            recList.appendChild(btn);
                        });
                    }
                }
            } catch (err) {
                console.error(err);
            }
        }

        // 7. Submit Reschedule Form
        async function submitRescheduleForm(e) {
            e.preventDefault();
            const payload = {
                schedule_id: document.getElementById('modalScheduleSelect').value,
                target_room_id: document.getElementById('modalTargetRoom').value,
                target_day: document.getElementById('modalTargetDay').value,
                target_time_slot: document.getElementById('modalTargetSlot').value,
                reason: document.getElementById('modalReason').value,
            };

            try {
                const res = await fetch('/api/reschedule/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.success) {
                    showToast(data.message, "success");
                    closeModal('rescheduleModal');
                    // Reload matrix
                    loadMatrix(currentFloor, currentDay);
                } else {
                    showToast(data.message, "error");
                }
            } catch (err) {
                showToast("Terjadi kesalahan saat mengirim pengajuan.", "error");
            }
        }

        // 8. Admin Actions: Approve, Reject, Reroute
        function openAdminQueueModal() {
            document.getElementById('adminQueueModal').classList.remove('hidden');
        }

        async function adminApprove(requestId) {
            if (!confirm("Konfirmasi: Setujui pengajuan reschedule ini? Ruangan baru akan dikunci resmi dan jadwal lama dibebaskan.")) return;
            try {
                const res = await fetch(`/api/admin/reschedule/${requestId}/approve`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, "success");
                    document.getElementById(`pendingItem-${requestId}`)?.remove();
                    updateBadgeCount(-1);
                    loadMatrix(currentFloor, currentDay);
                } else {
                    showToast(data.message, "error");
                }
            } catch (err) {
                showToast("Gagal menyetujui pengajuan.", "error");
            }
        }

        async function adminReject(requestId) {
            const reason = prompt("Masukkan alasan penolakan (akan dikirimkan ke dosen):", "Ruangan sedang dipersiapkan untuk kegiatan dinas fakultas");
            if (!reason) return;

            try {
                const res = await fetch(`/api/admin/reschedule/${requestId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({ reason })
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, "info");
                    document.getElementById(`pendingItem-${requestId}`)?.remove();
                    updateBadgeCount(-1);
                    loadMatrix(currentFloor, currentDay);
                } else {
                    showToast(data.message, "error");
                }
            } catch (err) {
                showToast("Gagal menolak pengajuan.", "error");
            }
        }

        function openRerouteDialog(requestId, day, slot, currentTargetRoomId) {
            document.getElementById('rerouteRequestId').value = requestId;
            document.getElementById('adminRerouteModal').classList.remove('hidden');
        }

        async function submitReroute() {
            const requestId = document.getElementById('rerouteRequestId').value;
            const newRoomId = document.getElementById('rerouteRoomSelect').value;

            try {
                const res = await fetch(`/api/admin/reschedule/${requestId}/reroute`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({ new_target_room_id: newRoomId })
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, "success");
                    closeModal('adminRerouteModal');
                    document.getElementById(`pendingItem-${requestId}`)?.remove();
                    updateBadgeCount(-1);
                    loadMatrix(currentFloor, currentDay);
                } else {
                    showToast(data.message, "error");
                }
            } catch (err) {
                showToast("Gagal mengalihkan ruangan.", "error");
            }
        }

        // 9. Admin Room Maintenance Toggle
        async function toggleRoomMaintenance(roomId, roomCode) {
            const reason = prompt(`Set status maintenance untuk ruangan ${roomCode}.\nMasukkan keterangan kerusakan:`, "Kerusakan AC & Perbaikan LCD Proyektor");
            if (reason === null) return;

            try {
                const res = await fetch(`/api/admin/room/${roomId}/maintenance`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({ reason: reason || 'Perbaikan Fasilitas Ruangan' })
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, "info");
                    loadMatrix(currentFloor, currentDay);
                }
            } catch (e) {
                showToast("Gagal mengubah status ruangan.", "error");
            }
        }

        function openQuickMaintenanceModal() {
            const roomCode = prompt("Masukkan nomor ruangan yang ingin diset Maintenance (contoh: GKU3-01-04):", `GKU3-${String(currentFloor).padStart(2,'0')}-01`);
            if (!roomCode) return;
            // Find room by code
            fetch(`/api/matrix?floor=${currentFloor}&day=${currentDay}`)
                .then(r => r.json())
                .then(d => {
                    const found = d.rooms.find(rm => rm.code.toLowerCase() === roomCode.trim().toLowerCase());
                    if (found) {
                        toggleRoomMaintenance(found.id, found.code);
                    } else {
                        showToast(`Ruangan ${roomCode} tidak ditemukan di lantai ${currentFloor}.`, "error");
                    }
                });
        }

        function openMyScheduleModal() {
            alert("Jadwal Mengajar Anda (Haris Ardian - CSA):\n\n1. BBK1CAB3 - MATEMATIKA UNTUK SISTEM INFORMASI\n   Hari: Selasa, 09:30 WIB @ Ruang GKU3-01-02\n   Kelas: BS1SI-50-REG-11 (42 Mahasiswa)\n\nSilakan klik tombol 'Ajukan Reschedule Kelas Saya' untuk memindahkan jam perkuliahan.");
        }

        function updateBadgeCount(delta) {
            const badge = document.getElementById('badgePendingCount');
            if (!badge) return;
            let current = parseInt(badge.textContent) || 0;
            current = Math.max(0, current + delta);
            badge.textContent = current;
            if (current === 0) badge.classList.add('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function showToast(msg, type = "success") {
            const toast = document.getElementById('toastBox');
            const toastMsg = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');

            toastMsg.textContent = msg;

            if (type === "success") {
                toastIcon.className = "fa-solid fa-circle-check text-emerald-400 text-base";
            } else if (type === "error") {
                toastIcon.className = "fa-solid fa-circle-xmark text-red-400 text-base";
            } else {
                toastIcon.className = "fa-solid fa-circle-info text-blue-400 text-base";
            }

            toast.classList.remove('translate-y-24', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-24', 'opacity-0');
            }, 4000);
        }
    </script>
</body>
</html>
