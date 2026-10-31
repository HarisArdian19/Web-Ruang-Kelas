<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Admin Gedung GKU 3 - Telkom University</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .matrix-container {
            max-height: calc(100vh - 290px);
            min-height: 520px;
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
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased">

    <!-- Top Navbar Admin (NO DOSEN ACCESS LINK) -->
    <header class="bg-slate-900 border-b border-slate-800 text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-[1700px] mx-auto px-4 py-2.5 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-xl shadow">
                    <i class="fa-solid fa-building-shield"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold text-white leading-tight">Portal Admin Gedung GKU 3</h1>
                        <span class="bg-blue-500/20 text-blue-400 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-500/30 uppercase">
                            Admin Otoritas Penuh
                        </span>
                    </div>
                    <p class="text-xs text-slate-400">Bagian Pengelolaan Gedung, Ruang Kelas & Logistik Telkom University</p>
                </div>
            </div>

            <!-- Action Buttons & Logout -->
            <div class="flex items-center gap-2.5">
                <button onclick="openAdminQueueModal()" class="px-3 py-1.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-900 text-xs font-extrabold flex items-center gap-1.5 shadow transition">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Antrean Reschedule</span>
                    <span id="badgePendingCount" class="bg-red-600 text-white text-[10px] px-1.5 py-0.2 rounded-full font-bold ml-0.5 {{ count($pendingRequests) > 0 ? '' : 'hidden' }}">
                        {{ count($pendingRequests) }}
                    </span>
                </button>

                <button onclick="openCancelledModal()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-semibold flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-calendar-xmark text-red-400"></i>
                    <span>Log Kelas Dibatalkan Dosen ({{ count($cancelledSchedules) }})</span>
                </button>

                <!-- Logout / Kembali ke Halaman Utama -->
                <a href="{{ route('portal.landing') }}" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold flex items-center gap-1.5 border border-slate-700 transition">
                    <i class="fa-solid fa-arrow-right-from-bracket text-slate-400"></i>
                    <span>Keluar Portal</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-[1700px] mx-auto px-4 py-4 space-y-3">

        <!-- Admin Control Strip -->
        <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white rounded-2xl p-4 shadow-md flex flex-wrap items-center justify-between gap-4 border border-blue-800/50">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-blue-600/30 border border-blue-400/30 flex items-center justify-center text-xl text-amber-300">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold tracking-wide">Pusat Otoritas & Verifikasi Ruangan GKU 3</h2>
                    <p class="text-xs text-blue-200">
                        Memiliki wewenang menyetujui perubahan jadwal, mengalihkan ruangan (*reroute*), atau mengunci ruangan bermasalah (*maintenance*).
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button onclick="openAdminQueueModal()" class="px-3.5 py-2 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-900 text-xs font-extrabold shadow flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-list-check"></i> Verifikasi Pengajuan Masuk ({{ count($pendingRequests) }})
                </button>
                <button onclick="openQuickMaintenanceModal()" class="px-3.5 py-2 rounded-xl bg-blue-800 hover:bg-blue-700 text-white border border-blue-600 text-xs font-bold flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-wrench text-amber-300"></i> Kunci Status Maintenance Ruang
                </button>
            </div>
        </div>

        <!-- Controls: Day & Floors -->
        <div class="bg-white rounded-xl p-3 shadow-sm border border-slate-200 space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1">
                        <i class="fa-regular fa-calendar text-blue-600"></i> Hari:
                    </span>
                    <div class="flex flex-wrap gap-1" id="dayButtons">
                        @foreach($days as $day)
                            <button onclick="changeDay('{{ $day }}')" 
                                class="day-btn px-3 py-1 rounded-lg text-xs font-semibold transition-all {{ $day === $defaultDay ? 'bg-blue-700 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}" 
                                data-day="{{ $day }}">
                                {{ $day }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-emerald-100 border border-emerald-500 inline-block"></span>
                        <span class="text-slate-600 font-medium">Terpakai (Kuliah)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-amber-100 border border-amber-400 inline-block"></span>
                        <span class="text-slate-600 font-medium">Pending Persetujuan</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-white border border-slate-300 inline-block"></span>
                        <span class="text-slate-600 font-medium">Ruang Kosong (Bebas)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-slate-700 border border-slate-900 inline-block"></span>
                        <span class="text-slate-600 font-medium">Maintenance (Rusak)</span>
                    </div>
                </div>
            </div>

            <!-- Floor Tabs -->
            <div class="pt-2 border-t border-slate-100 flex items-center gap-2">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1 mr-1">
                    <i class="fa-solid fa-stairs text-slate-500"></i> Lantai:
                </span>
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 w-full" id="floorTabs">
                    @for($f = 1; $f <= 10; $f++)
                        <button onclick="changeFloor({{ $f }})" 
                            class="floor-tab px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $f === $defaultFloor ? 'bg-blue-900 text-white shadow-sm ring-2 ring-blue-800' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}" 
                            data-floor="{{ $f }}">
                            LANTAI {{ $f }}
                        </button>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Matrix Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative">
            <div id="loadingOverlay" class="absolute inset-0 bg-white/70 backdrop-blur-sm z-50 flex items-center justify-center hidden">
                <div class="flex flex-col items-center gap-2">
                    <i class="fa-solid fa-circle-notch fa-spin text-3xl text-blue-600"></i>
                    <span class="text-xs font-bold text-slate-700">Memuat Jadwal Lantai...</span>
                </div>
            </div>

            <div class="matrix-container" id="matrixContainer">
                <table class="w-full border-collapse text-left" id="scheduleTable">
                    <thead>
                        <tr>
                            <th class="sticky-corner w-24 min-w-[96px] p-2.5 text-center text-xs font-bold uppercase text-slate-700 border-b border-r border-slate-300">
                                Jam
                            </th>
                            <tbody id="matrixBody"></tbody>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </main>

    <!-- MODAL 1: Admin Pending Queue Modal (Approve, Reroute, Reject) - FIXED SCROLLING -->
    <div id="adminQueueModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-3xl w-full shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[85vh] text-xs">
            <div class="bg-blue-950 px-5 py-4 text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-lg text-amber-400"></i>
                    <h3 class="font-bold text-base">Antrean Pengajuan Reschedule Menunggu Verifikasi Admin</h3>
                </div>
                <button type="button" onclick="closeModal('adminQueueModal')" class="text-white/80 hover:text-white text-lg p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="p-5 overflow-y-auto space-y-3 flex-1" id="pendingContainer">
                @forelse($pendingRequests as $req)
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-3" id="pendingItem-{{ $req->id }}">
                        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 pb-2">
                            <div>
                                <span class="bg-red-100 text-red-800 font-bold px-2 py-0.5 rounded text-[11px] mr-1.5">
                                    {{ $req->course_code }}
                                </span>
                                <strong class="text-slate-900 font-bold text-sm">{{ $req->course_name }}</strong>
                                <span class="text-slate-500 block">Dosen: <strong class="text-slate-700">{{ $req->lecturer_name }} ({{ $req->lecturer_code }})</strong> • Kelas: {{ $req->class_group }}</span>
                            </div>
                            <span class="bg-amber-100 text-amber-800 font-bold px-2 py-1 rounded-full border border-amber-300">
                                PENDING APPROVAL
                            </span>
                        </div>

                        <!-- Schedule Comparison -->
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
                                <span class="text-emerald-700 block text-[10px] font-bold uppercase">Target Pengajuan Dosen:</span>
                                <div class="font-bold text-emerald-900">
                                    <i class="fa-solid fa-calendar-check text-emerald-600 mr-1"></i> {{ $req->target_day }}, {{ $req->target_time_slot }} s.d. {{ $req->target_end_slot ?? '11:30' }} WIB
                                </div>
                                <div class="text-emerald-800 font-semibold">
                                    <i class="fa-solid fa-door-open text-emerald-600 mr-1"></i> Ruang: {{ $req->targetRoom->code ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-2 rounded-lg border border-slate-200 text-slate-600">
                            <strong>Alasan Dosen:</strong> {{ $req->reason }}
                        </div>

                        <!-- Admin Action Buttons -->
                        <div class="flex flex-wrap items-center justify-end gap-2 pt-1">
                            <button onclick="adminReject({{ $req->id }})" class="px-3 py-1.5 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-bold border border-red-300 flex items-center gap-1">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>
                            <button onclick="openRerouteDialog({{ $req->id }})" class="px-3 py-1.5 rounded-lg bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-bold border border-indigo-300 flex items-center gap-1">
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
            
            <div class="bg-slate-100 px-5 py-3 border-t shrink-0 text-right">
                <button type="button" onclick="closeModal('adminQueueModal')" class="px-4 py-2 rounded-lg bg-white border border-slate-300 text-slate-700 font-semibold text-xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL 2: Admin Reroute Dialog -->
    <div id="adminRerouteModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 p-5 space-y-4 text-xs">
            <div class="flex items-center justify-between border-b pb-2">
                <h4 class="font-bold text-sm text-slate-900 flex items-center gap-1.5">
                    <i class="fa-solid fa-shuffle text-indigo-600"></i> Alihkan Jadwal ke Ruang Alternatif
                </h4>
                <button type="button" onclick="closeModal('adminRerouteModal')" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <p class="text-slate-600">Pilih ruangan alternatif di GKU 3 yang tersedia pada slot waktu tersebut:</p>
            <input type="hidden" id="rerouteRequestId">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Pilih Ruangan Baru:</label>
                <select id="rerouteRoomSelect" class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 font-medium text-xs">
                </select>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t">
                <button type="button" onclick="closeModal('adminRerouteModal')" class="px-3 py-1.5 bg-slate-100 rounded-lg text-slate-600 font-semibold">Batal</button>
                <button type="button" onclick="submitReroute()" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow">Konfirmasi Alihkan</button>
            </div>
        </div>
    </div>

    <!-- MODAL 3: Log Kelas Dibatalkan Dosen - FIXED SCROLLING -->
    <div id="cancelledModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[85vh] text-xs">
            <div class="bg-slate-900 px-5 py-4 text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-xmark text-red-400 text-base"></i>
                    <h3 class="font-bold text-sm">Daftar Kelas Dibatalkan Dosen (Ruangan Bebas Digunakan)</h3>
                </div>
                <button type="button" onclick="closeModal('cancelledModal')" class="text-white/80 hover:text-white text-lg p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="p-5 overflow-y-auto space-y-3 flex-1">
                @forelse($cancelledSchedules as $cSch)
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50 space-y-1">
                        <div class="flex items-center justify-between">
                            <strong class="font-bold text-slate-900">{{ $cSch->course_code }} - {{ $cSch->course_name }}</strong>
                            <span class="bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded text-[10px]">DIBATALKAN</span>
                        </div>
                        <div class="text-slate-600">
                            Dosen: <strong>{{ $cSch->lecturer_name }}</strong> • Ruang: <strong>{{ $cSch->room->code }}</strong> (Lantai {{ $cSch->room->floor }})
                        </div>
                        <div class="text-[11px] text-slate-500">
                            Waktu Semula: {{ $cSch->day }}, {{ $cSch->time_slot }} WIB
                        </div>
                        <div class="bg-white p-2 rounded border border-slate-200 text-slate-600">
                            <strong>Alasan Pembatalan:</strong> {{ $cSch->cancellation_reason ?? 'Perkuliahan Ditiadakan' }}
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        Belum ada kelas yang dibatalkan oleh dosen.
                    </div>
                @endforelse
            </div>

            <div class="bg-slate-100 px-5 py-3 border-t shrink-0 text-right">
                <button type="button" onclick="closeModal('cancelledModal')" class="px-4 py-1.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-semibold text-xs">
                    Tutup
                </button>
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

    <!-- Script Admin -->
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let currentDay = '{{ $defaultDay }}';
        let currentFloor = {{ $defaultFloor }};
        let matrixData = null;

        document.addEventListener('DOMContentLoaded', () => {
            loadMatrix(currentFloor, currentDay);
            loadAllRoomsForReroute();
        });

        async function loadMatrix(floor, day) {
            document.getElementById('loadingOverlay').classList.remove('hidden');
            try {
                const res = await fetch(`/api/matrix?floor=${floor}&day=${encodeURIComponent(day)}`);
                const data = await res.json();
                matrixData = data;
                renderMatrix(data);
            } catch (err) {
                console.error(err);
                showToast("Gagal memuat jadwal.", "error");
            } finally {
                document.getElementById('loadingOverlay').classList.add('hidden');
            }
        }

        function changeDay(day) {
            currentDay = day;
            document.querySelectorAll('.day-btn').forEach(btn => {
                if (btn.dataset.day === day) {
                    btn.className = "day-btn px-3 py-1 rounded-lg text-xs font-semibold transition-all bg-blue-700 text-white shadow-sm";
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
                    tab.className = "floor-tab px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap bg-blue-900 text-white shadow-sm ring-2 ring-blue-800";
                } else {
                    tab.className = "floor-tab px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap bg-slate-100 text-slate-700 hover:bg-slate-200";
                }
            });
            loadMatrix(currentFloor, currentDay);
        }

        function renderMatrix(data) {
            const table = document.getElementById('scheduleTable');
            const theadTr = table.querySelector('thead tr');
            const tbody = document.getElementById('matrixBody');

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
                th.innerHTML = `
                    <div class="flex items-center justify-center gap-1">
                        <span>${room.code}</span>
                        <button onclick="toggleRoomMaintenance(${room.id}, '${room.code}')" title="Ubah status maintenance" class="text-[10px] font-normal underline text-blue-500 hover:text-blue-700">
                            [${room.status === 'maintenance' ? 'Aktifkan' : 'Set Rusak'}]
                        </button>
                    </div>
                    <span class="block text-[10px] font-normal text-slate-500">Kapasitas: ${room.capacity} Kursi</span>
                    ${room.status === 'maintenance' ? '<span class="block text-[10px] text-amber-200 font-normal"><i class="fa-solid fa-wrench"></i> Maintenance</span>' : ''}
                `;
                theadTr.appendChild(th);
            });

            tbody.innerHTML = '';

            data.timeSlots.forEach(slot => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50/50 transition-colors";

                const timeTd = document.createElement('td');
                timeTd.className = "sticky-col w-24 min-w-[96px] p-3 text-center text-xs font-bold text-slate-700 border-b border-r border-slate-300";
                timeTd.textContent = slot;
                tr.appendChild(timeTd);

                data.rooms.forEach(room => {
                    const td = document.createElement('td');
                    td.className = "min-w-[210px] w-[210px] p-2 border-b border-r border-slate-200 align-top";

                    const cellKey = `${room.id}_${slot}`;
                    const scheduleList = data.schedules[cellKey];
                    const pendingLock = data.pendingTargetSlots[cellKey];

                    if (room.status === 'maintenance') {
                        td.className += " bg-slate-200/90 text-slate-500";
                        td.innerHTML = `
                            <div class="p-2 rounded-lg border border-slate-300 bg-white/70 text-center space-y-1">
                                <i class="fa-solid fa-ban text-slate-400 text-sm"></i>
                                <span class="block text-[11px] font-bold text-slate-700">TIDAK TERSEDIA</span>
                                <span class="block text-[10px] text-slate-500">${room.maintenance_reason || 'Perbaikan Fasilitas'}</span>
                            </div>
                        `;
                    } else if (scheduleList && scheduleList.length > 0) {
                        const sch = scheduleList[0];
                        const isPending = sch.status === 'pending_reschedule';

                        td.className += isPending ? " bg-amber-50" : " bg-emerald-50/40";
                        td.innerHTML = `
                            <div class="p-2.5 rounded-xl border text-left shadow-xs ${
                                isPending ? 'bg-amber-100/90 border-amber-300 text-amber-900' : 'bg-[#dcfce7] border-[#86efac] text-slate-900'
                            }">
                                <div class="flex items-center justify-between gap-1 mb-1">
                                    <span class="font-extrabold text-[11px]">${sch.course_code}</span>
                                    <span class="text-[10px] font-bold px-1.5 py-0.2 rounded ${
                                        isPending ? 'bg-amber-300 text-amber-950' : 'bg-emerald-600 text-white'
                                    }">
                                        ${isPending ? 'Pending Reschedule' : sch.lecturer_code}
                                    </span>
                                </div>
                                <h4 class="font-bold text-[11px] line-clamp-2 leading-snug">${sch.course_name}</h4>
                                <div class="mt-1 pt-1 border-t border-emerald-200/70 text-[10px] text-slate-600 truncate">${sch.lecturer_name}</div>
                                <div class="text-[10px] text-slate-500 font-medium">Kelas: ${sch.class_group}</div>
                            </div>
                        `;
                    } else if (pendingLock) {
                        td.className += " bg-amber-50/80";
                        td.innerHTML = `
                            <div class="p-2.5 rounded-xl border border-amber-300 bg-amber-100 text-amber-900 text-left space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-extrabold uppercase">Pengajuan Dosen</span>
                                    <i class="fa-solid fa-lock text-amber-700 text-xs"></i>
                                </div>
                                <span class="font-bold text-[11px] block">${pendingLock.course_name}</span>
                                <span class="text-[10px] text-amber-800 block">Dosen: ${pendingLock.lecturer_name}</span>
                                <button onclick="openAdminQueueModal()" class="w-full bg-amber-300 hover:bg-amber-400 text-amber-950 text-[10px] font-bold py-1 rounded">
                                    Verifikasi Sekarang
                                </button>
                            </div>
                        `;
                    } else {
                        // Empty Slot
                        td.innerHTML = `
                            <div class="h-24 rounded-xl border border-dashed border-slate-200 hover:border-blue-400 bg-white hover:bg-blue-50/30 transition-all flex flex-col items-center justify-center p-2 text-center group cursor-pointer"
                                onclick="showToast('Slot ${room.code} ${currentDay} ${slot} WIB tersedia.', 'info')">
                                <span class="text-[11px] font-semibold text-slate-400 group-hover:text-blue-600 block">Tersedia</span>
                                <span class="mt-1 text-[10px] font-bold px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 group-hover:bg-blue-600 group-hover:text-white transition">
                                    Siap Dialokasikan
                                </span>
                            </div>
                        `;
                    }

                    tr.appendChild(td);
                });

                tbody.appendChild(tr);
            });
        }

        async function loadAllRoomsForReroute() {
            const select = document.getElementById('rerouteRoomSelect');
            select.innerHTML = '';
            for (let f = 1; f <= 10; f++) {
                const optGroup = document.createElement('optgroup');
                optGroup.label = `Lantai ${f}`;
                for (let r = 1; r <= 20; r++) {
                    const fStr = String(f).padStart(2, '0');
                    const rStr = String(r).padStart(2, '0');
                    const roomId = (f - 1) * 20 + r;
                    optGroup.appendChild(new Option(`GKU3-${fStr}-${rStr}`, roomId));
                }
                select.appendChild(optGroup);
            }
        }

        function openAdminQueueModal() {
            document.getElementById('adminQueueModal').classList.remove('hidden');
        }

        function openCancelledModal() {
            document.getElementById('cancelledModal').classList.remove('hidden');
        }

        async function adminApprove(requestId) {
            if (!confirm("Konfirmasi: Setujui pengajuan reschedule ini? Ruangan baru akan dikunci resmi dan email konfirmasi dikirim ke dosen.")) return;
            try {
                const res = await fetch(`/api/admin/reschedule/${requestId}/approve`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, "success");
                    document.getElementById(`pendingItem-${requestId}`)?.remove();
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
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ reason })
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, "info");
                    document.getElementById(`pendingItem-${requestId}`)?.remove();
                    loadMatrix(currentFloor, currentDay);
                } else {
                    showToast(data.message, "error");
                }
            } catch (err) {
                showToast("Gagal menolak pengajuan.", "error");
            }
        }

        function openRerouteDialog(requestId) {
            document.getElementById('rerouteRequestId').value = requestId;
            document.getElementById('adminRerouteModal').classList.remove('hidden');
        }

        async function submitReroute() {
            const requestId = document.getElementById('rerouteRequestId').value;
            const newRoomId = document.getElementById('rerouteRoomSelect').value;

            try {
                const res = await fetch(`/api/admin/reschedule/${requestId}/reroute`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ new_target_room_id: newRoomId })
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, "success");
                    closeModal('adminRerouteModal');
                    document.getElementById(`pendingItem-${requestId}`)?.remove();
                    loadMatrix(currentFloor, currentDay);
                } else {
                    showToast(data.message, "error");
                }
            } catch (err) {
                showToast("Gagal mengalihkan ruangan.", "error");
            }
        }

        async function toggleRoomMaintenance(roomId, roomCode) {
            const reason = prompt(`Set status maintenance untuk ruangan ${roomCode}.\nMasukkan keterangan kerusakan:`, "Kerusakan AC & Perbaikan LCD Proyektor");
            if (reason === null) return;

            try {
                const res = await fetch(`/api/admin/room/${roomId}/maintenance`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
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
            fetch(`/api/matrix?floor=${currentFloor}&day=${currentDay}`)
                .then(r => r.json())
                .then(d => {
                    const found = d.rooms.find(rm => rm.code.toLowerCase() === roomCode.trim().toLowerCase());
                    if (found) toggleRoomMaintenance(found.id, found.code);
                    else showToast(`Ruangan ${roomCode} tidak ditemukan di lantai ${currentFloor}.`, "error");
                });
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function showToast(msg, type = "success") {
            const toast = document.getElementById('toastBox');
            const toastMsg = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');
            toastMsg.textContent = msg;

            if (type === "success") toastIcon.className = "fa-solid fa-circle-check text-emerald-400 text-base";
            else if (type === "error") toastIcon.className = "fa-solid fa-circle-xmark text-red-400 text-base";
            else toastIcon.className = "fa-solid fa-circle-info text-blue-400 text-base";

            toast.classList.remove('translate-y-24', 'opacity-0');
            setTimeout(() => { toast.classList.add('translate-y-24', 'opacity-0'); }, 4500);
        }
    </script>
</body>
</html>
