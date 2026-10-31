<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Dosen - Sistem Ruang GKU 3 Telkom University</title>
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
        /* Custom scrollbar */
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

    <!-- Top Navbar Dosen -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-[1700px] mx-auto px-4 py-2.5 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-700 text-white flex items-center justify-center font-bold text-xl shadow-md">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold text-slate-900 leading-tight">Portal Dosen • Penjadwalan GKU 3</h1>
                        <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded border border-red-200 uppercase">
                            Mode Pengajar
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">Telkom University • Login sebagai: <strong>Haris Ardian, M.Kom. (CSA)</strong></p>
                </div>
            </div>

            <!-- Action & Inbox & Logout (NO ADMIN ACCESS LINK) -->
            <div class="flex items-center gap-2.5">
                <!-- Inbox Button -->
                <button onclick="openEmailModal()" class="relative px-3 py-1.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold flex items-center gap-1.5 shadow-sm transition">
                    <i class="fa-regular fa-envelope text-red-600 text-sm"></i>
                    <span>Kotak Masuk Email & H-1</span>
                    <span id="badgeEmailCount" class="bg-red-600 text-white text-[10px] px-1.5 py-0.2 rounded-full font-bold ml-0.5">
                        {{ count($myEmails) }}
                    </span>
                </button>

                <!-- Trigger H-1 simulation -->
                <button onclick="triggerH1Simulation()" title="Kirim simulasi notifikasi H-1 ke email dosen" class="px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-300 text-amber-900 text-xs font-bold flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-bell text-amber-600"></i>
                    <span>Simulasi Pengingat H-1</span>
                </button>

                <!-- Logout / Kembali ke Halaman Utama -->
                <a href="{{ route('portal.landing') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 border border-slate-300 transition">
                    <i class="fa-solid fa-arrow-right-from-bracket text-red-600"></i>
                    <span>Keluar Portal</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-[1700px] mx-auto px-4 py-4 space-y-3">

        <!-- Dosen Interactive Action Banner -->
        <div class="bg-gradient-to-r from-red-800 to-rose-900 text-white rounded-2xl p-4 shadow-md flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-xl text-amber-300">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold tracking-wide">Pusat Layanan Jadwal Mengajar Dosen (CSA)</h2>
                    <p class="text-xs text-red-100">
                        Ajukan perubahan jadwal dengan rentang jam mulai & selesai (multi-slot), atau batalkan perkuliahan yang ditiadakan agar ruangan dapat dipakai dosen lain.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button onclick="openRescheduleModal()" class="px-3.5 py-2 rounded-xl bg-white text-red-800 hover:bg-red-50 text-xs font-extrabold shadow-sm flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-clock-rotate-left"></i> Ajukan Reschedule (Rentang Jam)
                </button>
                <button onclick="openCancellationModal()" class="px-3.5 py-2 rounded-xl bg-red-950/70 hover:bg-red-950 text-white border border-red-400/40 text-xs font-bold flex items-center gap-1.5 transition shadow">
                    <i class="fa-solid fa-ban text-amber-300"></i> Batalkan Jadwal Kuliah (Tidak Jadi Kelas)
                </button>
            </div>
        </div>

        <!-- Controls: Day Selector & Floor Tabs -->
        <div class="bg-white rounded-xl p-3 shadow-sm border border-slate-200 space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Days -->
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

                <!-- Legend -->
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
                        <span class="text-slate-600 font-medium">Ruang Kosong (Bebas Dipilih)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3.5 h-3.5 rounded bg-slate-700 border border-slate-900 inline-block"></span>
                        <span class="text-slate-600 font-medium">Maintenance (Rusak)</span>
                    </div>
                </div>
            </div>

            <!-- Floor Tabs (Lantai 1 sampai 10) -->
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

    <!-- MODAL 1: Reschedule Rentang Waktu (Jam Mulai s.d. Jam Selesai) -->
    <div id="rescheduleModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Header (Always visible) -->
            <div class="bg-red-700 px-5 py-4 text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-plus text-lg"></i>
                    <h3 class="font-bold text-base">Ajukan Reschedule (Rentang Jam)</h3>
                </div>
                <button type="button" onclick="closeModal('rescheduleModal')" class="text-white/80 hover:text-white text-lg p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <!-- Body (Scrollable) -->
            <form id="rescheduleForm" onsubmit="submitRescheduleForm(event)" class="p-5 space-y-4 text-xs overflow-y-auto flex-1">
                <!-- Class select -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Kelas yang Akan Dipindahkan:</label>
                    <select id="modalScheduleSelect" required class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-red-500 focus:outline-none">
                        @foreach($mySchedules as $sch)
                            @if($sch->status !== 'cancelled')
                                <option value="{{ $sch->id }}" data-day="{{ $sch->day }}" data-slot="{{ $sch->time_slot }}" data-room="{{ $sch->room->code ?? '' }}">
                                    {{ $sch->course_code }} - {{ $sch->course_name }} ({{ $sch->day }} {{ $sch->time_slot }} @ {{ $sch->room->code ?? '' }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Target Day -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Target Hari Baru:</label>
                    <select id="modalTargetDay" onchange="runLiveConflictCheck()" required class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2 text-xs font-medium focus:ring-2 focus:ring-red-500 focus:outline-none">
                        @foreach($days as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Range: Jam Mulai & Jam Selesai -->
                <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            <i class="fa-regular fa-clock text-red-600"></i> Jam Mulai:
                        </label>
                        <select id="modalTargetStartSlot" onchange="updateEndTimeOptions(); runLiveConflictCheck()" required class="w-full bg-white border border-slate-300 rounded-lg p-2 text-xs font-medium focus:ring-2 focus:ring-red-500 focus:outline-none">
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ $slot }} WIB</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            <i class="fa-regular fa-clock text-red-600"></i> Jam Selesai:
                        </label>
                        <select id="modalTargetEndSlot" onchange="runLiveConflictCheck()" required class="w-full bg-white border border-slate-300 rounded-lg p-2 text-xs font-medium focus:ring-2 focus:ring-red-500 focus:outline-none">
                            @foreach($endTimeSlots as $eSlot)
                                <option value="{{ $eSlot }}">{{ $eSlot }} WIB</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Target Room -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Target Ruangan GKU 3:</label>
                    <select id="modalTargetRoom" onchange="runLiveConflictCheck()" required class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-xs font-medium focus:ring-2 focus:ring-red-500 focus:outline-none">
                    </select>
                </div>

                <!-- Live Collision Check Indicator Box -->
                <div id="collisionAlertBox" class="p-3 rounded-xl border text-xs flex items-start gap-2.5 bg-slate-50 border-slate-200">
                    <i id="collisionIcon" class="fa-solid fa-spinner fa-spin text-slate-500 mt-0.5"></i>
                    <div id="collisionText" class="flex-1">
                        <span class="font-semibold text-slate-600">Memeriksa ketersediaan ruangan...</span>
                    </div>
                </div>

                <!-- Smart Recommendations Container -->
                <div id="recommendationContainer" class="hidden bg-amber-50 border border-amber-200 rounded-xl p-3 space-y-2">
                    <span class="font-bold text-amber-900 block flex items-center gap-1.5">
                        <i class="fa-solid fa-lightbulb text-amber-600"></i> Ruangan Alternatif Terdekat yang Tersedia:
                    </span>
                    <div id="recommendationList" class="space-y-1.5"></div>
                </div>

                <!-- Reason -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Alasan Perubahan Jadwal (Wajib):</label>
                    <textarea id="modalReason" required rows="2" placeholder="Contoh: Maju sehari karena bentrok jadwal dinas..." class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2 text-xs focus:ring-2 focus:ring-red-500 focus:outline-none"></textarea>
                </div>
            </form>

            <!-- Footer (Always visible at bottom) -->
            <div class="p-4 bg-slate-100 border-t border-slate-200 flex items-center justify-end gap-2 shrink-0">
                <button type="button" onclick="closeModal('rescheduleModal')" class="px-4 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs">
                    Batal
                </button>
                <button type="button" onclick="document.getElementById('rescheduleForm').requestSubmit()" id="btnSubmitReschedule" class="px-4 py-2 rounded-lg bg-red-700 hover:bg-red-800 text-white font-bold shadow-md flex items-center gap-1.5 transition text-xs">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan ke Admin
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL 2: Pembatalan Kelas (Dosen Tidak Jadi Kuliah) - FIXED SCROLLING -->
    <div id="cancellationModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[85vh] text-xs">
            
            <!-- Fixed Header -->
            <div class="bg-slate-900 px-5 py-4 text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-red-600/30 border border-red-500/40 flex items-center justify-center text-red-400">
                        <i class="fa-solid fa-ban text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm leading-tight">Pembatalan Jadwal Kuliah (Tidak Jadi Kelas)</h3>
                        <p class="text-[11px] text-slate-400">Ruangan akan otomatis dibebaskan dan siap dipakai oleh dosen lain</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('cancellationModal')" class="text-slate-400 hover:text-white text-lg p-1 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Search & Filter bar inside Modal -->
            <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 shrink-0 flex flex-wrap items-center justify-between gap-2">
                <div class="relative flex-1 min-w-[200px]">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    <input type="text" id="cancelFilterInput" onkeyup="filterCancellationList()" placeholder="Cari berdasarkan hari, ruang, atau nama mata kuliah..." class="w-full bg-white border border-slate-300 rounded-lg pl-8 pr-3 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-red-500">
                </div>
                <span class="text-[11px] text-slate-500 font-medium">
                    Total: <strong id="totalCancelCount" class="text-slate-800">{{ count($mySchedules) }}</strong> Jadwal Mengajar
                </span>
            </div>
            
            <!-- Scrollable Body with Fixed Height -->
            <div class="p-5 overflow-y-auto space-y-2.5 flex-1" id="cancelListContainer">
                @forelse($mySchedules as $sch)
                    <div class="cancel-item p-3.5 rounded-xl border {{ $sch->status === 'cancelled' ? 'border-slate-200 bg-slate-100 opacity-60' : 'border-slate-200 bg-white hover:border-slate-300' }} flex flex-wrap items-center justify-between gap-3 transition"
                        data-search="{{ strtolower($sch->course_name . ' ' . $sch->course_code . ' ' . $sch->day . ' ' . ($sch->room->code ?? '')) }}">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="bg-red-100 text-red-800 font-bold px-2 py-0.5 rounded text-[10px]">
                                    {{ $sch->course_code }}
                                </span>
                                <strong class="text-slate-900 font-bold text-xs">{{ $sch->course_name }}</strong>
                            </div>
                            <div class="text-slate-600 mt-1 flex items-center gap-3 text-[11px]">
                                <span><i class="fa-regular fa-calendar text-slate-400 mr-1"></i><strong>{{ $sch->day }}</strong>, {{ $sch->time_slot }} WIB</span>
                                <span><i class="fa-solid fa-door-open text-slate-400 mr-1"></i>Ruang: <strong>{{ $sch->room->code ?? '-' }}</strong></span>
                                <span><i class="fa-solid fa-users text-slate-400 mr-1"></i>{{ $sch->class_group }}</span>
                            </div>
                        </div>

                        <div>
                            @if($sch->status === 'cancelled')
                                <span class="bg-slate-200 text-slate-600 px-3 py-1 rounded-full font-bold text-[10px] inline-flex items-center gap-1">
                                    <i class="fa-solid fa-check"></i> Telah Dibatalkan
                                </span>
                            @else
                                <button type="button" onclick="confirmClassCancellation({{ $sch->id }}, '{{ $sch->course_name }}', '{{ $sch->day }} {{ $sch->time_slot }}')" class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white font-bold text-[11px] shadow-sm flex items-center gap-1 transition">
                                    <i class="fa-solid fa-ban"></i> Batalkan Kelas
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">Tidak ada jadwal mengajar aktif.</div>
                @endforelse
            </div>

            <!-- Fixed Footer with Close Button (Always visible) -->
            <div class="bg-slate-100 px-5 py-3 border-t border-slate-200 shrink-0 flex items-center justify-between">
                <span class="text-[11px] text-slate-500">
                    <i class="fa-solid fa-circle-info text-blue-500 mr-1"></i> Pembatalan akan otomatis mengirim email konfirmasi ke Anda dan mahasiswa.
                </span>
                <button type="button" onclick="closeModal('cancellationModal')" class="px-5 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL 3: Kotak Masuk Email Notifikasi & Pengingat H-1 - FIXED SCROLLING -->
    <div id="emailModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[85vh] text-xs">
            <!-- Header -->
            <div class="bg-red-800 px-5 py-4 text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-envelope-open text-amber-300 text-base"></i>
                    <h3 class="font-bold text-sm">Kotak Masuk Email Dosen (haris.ardian@telkomuniversity.ac.id)</h3>
                </div>
                <button type="button" onclick="closeModal('emailModal')" class="text-white/80 hover:text-white text-lg p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <!-- Scrollable Body -->
            <div class="p-5 overflow-y-auto space-y-3 flex-1" id="emailListContainer">
                @forelse($myEmails as $mail)
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-2 hover:bg-slate-100/70 transition">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-900 flex items-center gap-1.5 text-xs">
                                @if($mail->type === 'reminder_h1')
                                    <i class="fa-solid fa-bell text-amber-500"></i>
                                @elseif($mail->type === 'cancellation')
                                    <i class="fa-solid fa-ban text-red-500"></i>
                                @else
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                @endif
                                {{ $mail->subject }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium">
                                {{ $mail->created_at->diffForHumans() }}
                            </span>
                        </div>
                        @if($mail->class_info)
                            <span class="inline-block bg-white border border-slate-200 px-2 py-0.5 rounded text-[10px] text-slate-600 font-semibold">
                                {{ $mail->class_info }}
                            </span>
                        @endif
                        <p class="text-slate-600 whitespace-pre-line bg-white p-3 rounded-lg border border-slate-200 text-[11px] leading-relaxed">
                            {{ $mail->body }}
                        </p>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        <i class="fa-regular fa-envelope text-3xl mb-2 block"></i>
                        Belum ada email notifikasi atau pengingat H-1 yang diterima.
                    </div>
                @endforelse
            </div>

            <!-- Footer -->
            <div class="bg-slate-100 px-5 py-3 border-t shrink-0 flex items-center justify-between">
                <button type="button" onclick="triggerH1Simulation()" class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold text-xs flex items-center gap-1">
                    <i class="fa-solid fa-bell"></i> Kirim Ulang Pengingat H-1
                </button>
                <button type="button" onclick="closeModal('emailModal')" class="px-4 py-1.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-semibold text-xs">
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

    <!-- Script Logic Dosen -->
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let currentDay = '{{ $defaultDay }}';
        let currentFloor = {{ $defaultFloor }};
        let matrixData = null;

        document.addEventListener('DOMContentLoaded', () => {
            loadMatrix(currentFloor, currentDay);
            loadAllRoomsForDropdown();
            updateEndTimeOptions();
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
                showToast("Gagal memuat matriks jadwal.", "error");
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
                    <div class="font-bold">${room.code}</div>
                    <span class="block text-[10px] font-normal text-slate-500">Kapasitas: ${room.capacity} Kursi</span>
                    ${room.status === 'maintenance' ? '<span class="block text-[10px] text-amber-300 font-normal"><i class="fa-solid fa-wrench"></i> Maintenance</span>' : ''}
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
                                        ${isPending ? 'Menunggu Admin' : sch.lecturer_code}
                                    </span>
                                </div>
                                <h4 class="font-bold text-[11px] line-clamp-2 leading-snug">${sch.course_name}</h4>
                                <div class="mt-1 pt-1 border-t border-emerald-200/70 text-[10px] text-slate-600 truncate">${sch.lecturer_name}</div>
                                <div class="text-[10px] text-slate-500 font-medium">Kelas: ${sch.class_group}</div>
                                ${sch.lecturer_code === 'CSA' ? `
                                    <button onclick="directReschedule(${sch.id})" class="mt-1.5 w-full bg-red-700 hover:bg-red-800 text-white text-[10px] font-bold py-1 px-2 rounded flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-clock-rotate-left"></i> Reschedule
                                    </button>
                                ` : ''}
                            </div>
                        `;
                    } else if (pendingLock) {
                        td.className += " bg-amber-50/80";
                        td.innerHTML = `
                            <div class="p-2.5 rounded-xl border border-amber-300 bg-amber-100 text-amber-900 text-left space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-extrabold uppercase">Sedang Diajukan</span>
                                    <i class="fa-solid fa-lock text-amber-700 text-xs"></i>
                                </div>
                                <span class="font-bold text-[11px] block">${pendingLock.course_name}</span>
                                <span class="text-[10px] text-amber-800 block">Dosen: ${pendingLock.lecturer_name}</span>
                                <span class="text-[9px] bg-amber-200 text-amber-900 px-1 rounded block text-center font-semibold">Terkunci Sementara</span>
                            </div>
                        `;
                    } else {
                        // Empty Slot
                        td.innerHTML = `
                            <div class="h-24 rounded-xl border border-dashed border-slate-200 hover:border-red-400 bg-white hover:bg-red-50/30 transition-all flex flex-col items-center justify-center p-2 text-center cursor-pointer group"
                                onclick="openRescheduleModal(${room.id}, '${currentDay}', '${slot}')">
                                <span class="text-[11px] font-semibold text-slate-400 group-hover:text-red-600 block">Tersedia</span>
                                <span class="mt-1 text-[10px] font-bold px-2 py-0.5 rounded-md bg-red-50 text-red-700 group-hover:bg-red-700 group-hover:text-white transition">
                                    + Ajukan Pindah
                                </span>
                            </div>
                        `;
                    }

                    tr.appendChild(td);
                });

                tbody.appendChild(tr);
            });
        }

        // Calculate and update valid end time options based on selected start time
        function updateEndTimeOptions() {
            const startVal = document.getElementById('modalTargetStartSlot').value;
            const endSelect = document.getElementById('modalTargetEndSlot');
            const allEndSlots = ['07:30', '08:30', '09:30', '10:30', '11:30', '12:30', '13:30', '14:30', '15:30', '16:30', '17:30', '18:30', '19:30'];

            const startIdx = parseInt(startVal.split(':')[0]);
            endSelect.innerHTML = '';

            allEndSlots.forEach(e => {
                const eIdx = parseInt(e.split(':')[0]);
                if (eIdx > startIdx && eIdx <= startIdx + 4) {
                    const dur = eIdx - startIdx;
                    endSelect.appendChild(new Option(`${e} WIB (${dur} Jam / SKS)`, e));
                }
            });
        }

        async function loadAllRoomsForDropdown() {
            const select = document.getElementById('modalTargetRoom');
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

        function openRescheduleModal(targetRoomId = null, targetDay = null, targetTimeSlot = null) {
            document.getElementById('rescheduleModal').classList.remove('hidden');
            if (targetDay) document.getElementById('modalTargetDay').value = targetDay;
            if (targetTimeSlot) {
                document.getElementById('modalTargetStartSlot').value = targetTimeSlot;
                updateEndTimeOptions();
            }
            if (targetRoomId) document.getElementById('modalTargetRoom').value = targetRoomId;
            runLiveConflictCheck();
        }

        function directReschedule(schId) {
            document.getElementById('modalScheduleSelect').value = schId;
            openRescheduleModal();
        }

        async function runLiveConflictCheck() {
            const roomId = document.getElementById('modalTargetRoom').value;
            const day = document.getElementById('modalTargetDay').value;
            const startSlot = document.getElementById('modalTargetStartSlot').value;
            const endSlot = document.getElementById('modalTargetEndSlot').value;

            const box = document.getElementById('collisionAlertBox');
            const icon = document.getElementById('collisionIcon');
            const text = document.getElementById('collisionText');
            const recContainer = document.getElementById('recommendationContainer');
            const recList = document.getElementById('recommendationList');
            const btnSubmit = document.getElementById('btnSubmitReschedule');

            box.className = "p-3 rounded-xl border text-xs flex items-start gap-2.5 bg-slate-50 border-slate-200";
            icon.className = "fa-solid fa-spinner fa-spin text-slate-500 mt-0.5";
            text.innerHTML = `<span class="font-semibold text-slate-600">Memeriksa ketersediaan seluruh rentang jam (${startSlot} - ${endSlot} WIB)...</span>`;
            recContainer.classList.add('hidden');

            try {
                const res = await fetch('/api/conflict/check', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({
                        target_room_id: roomId,
                        target_day: day,
                        target_time_slot: startSlot,
                        target_end_slot: endSlot
                    })
                });
                const data = await res.json();

                if (!data.has_conflict) {
                    box.className = "p-3 rounded-xl border text-xs flex items-start gap-2.5 bg-emerald-50 border-emerald-300 text-emerald-900";
                    icon.className = "fa-solid fa-circle-check text-emerald-600 text-base mt-0.5";
                    text.innerHTML = `<strong class="block text-emerald-800 font-bold">100% BEBAS BENTROK (${startSlot} s.d. ${endSlot} WIB)</strong><span>${data.message}</span>`;
                    btnSubmit.disabled = false;
                    btnSubmit.className = "px-4 py-2 rounded-lg bg-red-700 hover:bg-red-800 text-white font-bold shadow-md flex items-center gap-1.5 transition text-xs";
                } else {
                    box.className = "p-3 rounded-xl border text-xs flex items-start gap-2.5 bg-red-50 border-red-300 text-red-900";
                    icon.className = "fa-solid fa-triangle-exclamation text-red-600 text-base mt-0.5";
                    text.innerHTML = `<strong class="block text-red-800 font-bold">BENTROK TERDETEKSI!</strong><span>${data.message}</span>`;
                    btnSubmit.disabled = true;
                    btnSubmit.className = "px-4 py-2 rounded-lg bg-slate-300 text-slate-500 font-bold cursor-not-allowed flex items-center gap-1.5 text-xs";

                    if (data.recommendations && (data.recommendations.same_floor.length > 0 || data.recommendations.adjacent_floor.length > 0)) {
                        recContainer.classList.remove('hidden');
                        recList.innerHTML = '';
                        data.recommendations.same_floor.forEach(rm => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = "w-full text-left bg-white hover:bg-emerald-50 p-2 rounded-lg border border-amber-300 flex items-center justify-between";
                            btn.innerHTML = `<div><strong class="text-emerald-700">${rm.code}</strong> (Lantai ${rm.floor} - Satu Lantai)<span class="block text-[10px] text-slate-500">Kapasitas: ${rm.capacity} Kursi</span></div><span class="bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded">Pilih Ini</span>`;
                            btn.onclick = () => { document.getElementById('modalTargetRoom').value = rm.id; runLiveConflictCheck(); };
                            recList.appendChild(btn);
                        });
                        data.recommendations.adjacent_floor.forEach(rm => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = "w-full text-left bg-white hover:bg-slate-50 p-2 rounded-lg border border-slate-200 flex items-center justify-between";
                            btn.innerHTML = `<div><strong class="text-slate-800">${rm.code}</strong> (Lantai ${rm.floor} - Lantai Dekat)<span class="block text-[10px] text-slate-500">Kapasitas: ${rm.capacity} Kursi</span></div><span class="bg-slate-200 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded">Pilih Ini</span>`;
                            btn.onclick = () => { document.getElementById('modalTargetRoom').value = rm.id; runLiveConflictCheck(); };
                            recList.appendChild(btn);
                        });
                    }
                }
            } catch (err) {
                console.error(err);
            }
        }

        async function submitRescheduleForm(e) {
            e.preventDefault();
            const payload = {
                schedule_id: document.getElementById('modalScheduleSelect').value,
                target_room_id: document.getElementById('modalTargetRoom').value,
                target_day: document.getElementById('modalTargetDay').value,
                target_time_slot: document.getElementById('modalTargetStartSlot').value,
                target_end_slot: document.getElementById('modalTargetEndSlot').value,
                reason: document.getElementById('modalReason').value,
            };

            try {
                const res = await fetch('/api/reschedule/submit', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, "success");
                    closeModal('rescheduleModal');
                    loadMatrix(currentFloor, currentDay);
                } else {
                    showToast(data.message, "error");
                }
            } catch (err) {
                showToast("Gagal mengirim pengajuan.", "error");
            }
        }

        // Cancel Class Action
        function openCancellationModal() {
            document.getElementById('cancellationModal').classList.remove('hidden');
        }

        function filterCancellationList() {
            const query = document.getElementById('cancelFilterInput').value.toLowerCase().trim();
            const items = document.querySelectorAll('.cancel-item');
            let visibleCount = 0;

            items.forEach(item => {
                const text = item.getAttribute('data-search') || '';
                if (text.includes(query)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            document.getElementById('totalCancelCount').textContent = visibleCount;
        }

        async function confirmClassCancellation(scheduleId, courseName, timeInfo) {
            const reason = prompt(`Konfirmasi Pembatalan Perkuliahan:\n\nMata Kuliah: ${courseName}\nWaktu: ${timeInfo}\n\nMasukkan alasan pembatalan kuliah (ruangan akan langsung dibebaskan untuk dosen lain):`, "Dosen berhalangan hadir / perkuliahan ditiadakan");
            if (!reason) return;

            try {
                const res = await fetch(`/api/dosen/class/${scheduleId}/cancel`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ cancellation_reason: reason })
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, "success");
                    closeModal('cancellationModal');
                    loadMatrix(currentFloor, currentDay);
                    updateEmailCount(1);
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showToast(data.message, "error");
                }
            } catch (e) {
                showToast("Gagal membatalkan jadwal perkuliahan.", "error");
            }
        }

        // Trigger H-1 simulation
        async function triggerH1Simulation() {
            try {
                const res = await fetch('/api/dosen/trigger-h1-reminders', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, "success");
                    openEmailModal();
                } else {
                    showToast(data.message, "error");
                }
            } catch (e) {
                showToast("Gagal memicu pengingat H-1.", "error");
            }
        }

        async function openEmailModal() {
            document.getElementById('emailModal').classList.remove('hidden');
            try {
                const res = await fetch('/api/dosen/emails');
                const data = await res.json();
                const container = document.getElementById('emailListContainer');
                container.innerHTML = '';
                if (data.emails.length === 0) {
                    container.innerHTML = `<div class="text-center py-8 text-slate-400">Belum ada email yang diterima.</div>`;
                    return;
                }
                data.emails.forEach(mail => {
                    const div = document.createElement('div');
                    div.className = "p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-2";
                    div.innerHTML = `
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-900 flex items-center gap-1.5 text-xs">
                                <i class="fa-solid fa-bell text-amber-500"></i> ${mail.subject}
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium">${new Date(mail.created_at).toLocaleTimeString()}</span>
                        </div>
                        ${mail.class_info ? `<span class="inline-block bg-white border border-slate-200 px-2 py-0.5 rounded text-[10px] text-slate-600 font-semibold">${mail.class_info}</span>` : ''}
                        <p class="text-slate-600 whitespace-pre-line bg-white p-3 rounded-lg border border-slate-200 text-[11px] leading-relaxed">${mail.body}</p>
                    `;
                    container.appendChild(div);
                });
            } catch (e) {
                console.error(e);
            }
        }

        function updateEmailCount(delta) {
            const badge = document.getElementById('badgeEmailCount');
            if (badge) {
                let current = parseInt(badge.textContent) || 0;
                badge.textContent = current + delta;
            }
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
