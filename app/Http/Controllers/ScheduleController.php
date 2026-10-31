<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Schedule;
use App\Models\RescheduleRequest;
use App\Models\EmailNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    protected array $timeSlots = [
        '06:30', '07:30', '08:30', '09:30', '10:30',
        '11:30', '12:30', '13:30', '14:30', '15:30',
        '16:30', '17:30', '18:30'
    ];

    protected array $endTimeSlots = [
        '07:30', '08:30', '09:30', '10:30', '11:30',
        '12:30', '13:30', '14:30', '15:30', '16:30',
        '17:30', '18:30', '19:30'
    ];

    protected array $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    /**
     * Landing / Portal Chooser
     */
    public function landing()
    {
        $pendingCount = RescheduleRequest::where('status', 'pending')->count();
        $activeRooms = Room::where('status', 'active')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();
        $totalSchedules = Schedule::where('status', 'confirmed')->count();
        $cancelledCount = Schedule::where('status', 'cancelled')->count();

        return view('landing', compact(
            'pendingCount', 'activeRooms', 'maintenanceRooms', 'totalSchedules', 'cancelledCount'
        ));
    }

    /**
     * Dosen Portal View
     */
    public function dosenPortal(Request $request)
    {
        $defaultFloor = (int) $request->get('floor', 1);
        $defaultDay = $request->get('day', 'Selasa');

        // Haris Ardian (CSA) schedules
        $mySchedules = Schedule::with('room')
            ->where('lecturer_code', 'CSA')
            ->orderBy('day')
            ->orderBy('time_slot')
            ->get();

        $myEmails = EmailNotification::where('lecturer_name', 'like', '%Haris Ardian%')
            ->orWhere('lecturer_email', 'like', '%haris%')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $myPendingRequests = RescheduleRequest::with(['fromRoom', 'targetRoom'])
            ->where('lecturer_code', 'CSA')
            ->where('status', 'pending')
            ->get();

        return view('dosen.index', [
            'days' => $this->days,
            'timeSlots' => $this->timeSlots,
            'endTimeSlots' => $this->endTimeSlots,
            'defaultFloor' => $defaultFloor,
            'defaultDay' => $defaultDay,
            'mySchedules' => $mySchedules,
            'myEmails' => $myEmails,
            'myPendingRequests' => $myPendingRequests,
        ]);
    }

    /**
     * Admin Portal View
     */
    public function adminPortal(Request $request)
    {
        $defaultFloor = (int) $request->get('floor', 1);
        $defaultDay = $request->get('day', 'Selasa');

        $pendingRequests = RescheduleRequest::with(['fromRoom', 'targetRoom', 'schedule'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $maintenanceRooms = Room::where('status', 'maintenance')
            ->orderBy('floor')
            ->orderBy('room_number')
            ->get();

        $cancelledSchedules = Schedule::with('room')
            ->where('status', 'cancelled')
            ->orderBy('cancelled_at', 'desc')
            ->take(15)
            ->get();

        return view('admin.index', [
            'days' => $this->days,
            'timeSlots' => $this->timeSlots,
            'endTimeSlots' => $this->endTimeSlots,
            'defaultFloor' => $defaultFloor,
            'defaultDay' => $defaultDay,
            'pendingRequests' => $pendingRequests,
            'maintenanceRooms' => $maintenanceRooms,
            'cancelledSchedules' => $cancelledSchedules,
        ]);
    }

    /**
     * Get Matrix Data for a Floor & Day
     */
    public function getMatrixData(Request $request): JsonResponse
    {
        $floor = (int) $request->get('floor', 1);
        $day = $request->get('day', 'Selasa');

        $rooms = Room::where('floor', $floor)
            ->orderBy('room_number', 'asc')
            ->get();

        $roomIds = $rooms->pluck('id');

        // Confirmed schedules (exclude cancelled so slot becomes free!)
        $schedules = Schedule::whereIn('room_id', $roomIds)
            ->where('day', $day)
            ->where('status', '!=', 'cancelled')
            ->get()
            ->groupBy(fn ($item) => "{$item->room_id}_{$item->time_slot}");

        $pendingTargetSlots = RescheduleRequest::where('status', 'pending')
            ->whereIn('target_room_id', $roomIds)
            ->where('target_day', $day)
            ->get();

        $pendingSlotsMapped = [];
        foreach ($pendingTargetSlots as $pReq) {
            $slots = $this->getSlotsInRange($pReq->target_time_slot, $pReq->target_end_slot ?? $this->getNextSlot($pReq->target_time_slot));
            foreach ($slots as $sl) {
                $pendingSlotsMapped["{$pReq->target_room_id}_{$sl}"] = $pReq;
            }
        }

        return response()->json([
            'floor' => $floor,
            'day' => $day,
            'rooms' => $rooms,
            'schedules' => $schedules,
            'pendingTargetSlots' => $pendingSlotsMapped,
            'timeSlots' => $this->timeSlots,
        ]);
    }

    /**
     * Check Conflict for a Time Range (Start Time to End Time)
     */
    public function checkConflict(Request $request): JsonResponse
    {
        $request->validate([
            'target_room_id' => 'required|exists:rooms,id',
            'target_day' => 'required|string',
            'target_time_slot' => 'required|string',
            'target_end_slot' => 'nullable|string',
        ]);

        $room = Room::findOrFail($request->target_room_id);
        $day = $request->target_day;
        $startSlot = $request->target_time_slot;
        $endSlot = $request->target_end_slot ?? $this->getNextSlot($startSlot);

        $slotsToCheck = $this->getSlotsInRange($startSlot, $endSlot);

        if (empty($slotsToCheck)) {
            return response()->json([
                'has_conflict' => true,
                'message' => 'Jam Selesai harus lebih lambat dari Jam Mulai.',
                'recommendations' => [],
            ], 422);
        }

        // 1. Check room maintenance
        if ($room->status === 'maintenance') {
            $recommendations = $this->findAvailableRoomsMultiSlot($room->floor, $day, $slotsToCheck);
            return response()->json([
                'has_conflict' => true,
                'type' => 'maintenance',
                'message' => "Ruangan {$room->code} sedang MAINTENANCE ({$room->maintenance_reason}).",
                'recommendations' => $recommendations,
            ]);
        }

        // 2. Check existing confirmed schedules in any of the slots
        $existing = Schedule::where('room_id', $room->id)
            ->where('day', $day)
            ->where('status', '!=', 'cancelled')
            ->whereIn('time_slot', $slotsToCheck)
            ->first();

        if ($existing) {
            $recommendations = $this->findAvailableRoomsMultiSlot($room->floor, $day, $slotsToCheck);
            return response()->json([
                'has_conflict' => true,
                'type' => 'schedule_conflict',
                'message' => "JADWAL BENTROK! Ruang {$room->code} pada jam {$existing->time_slot} WIB sudah terisi oleh {$existing->course_name} ({$existing->lecturer_name} - {$existing->class_group}).",
                'conflict_schedule' => $existing,
                'recommendations' => $recommendations,
            ]);
        }

        // 3. Check pending reschedule request locking any of the slots
        $pendingReqs = RescheduleRequest::where('status', 'pending')
            ->where('target_room_id', $room->id)
            ->where('target_day', $day)
            ->get();

        foreach ($pendingReqs as $pReq) {
            $pSlots = $this->getSlotsInRange($pReq->target_time_slot, $pReq->target_end_slot ?? $this->getNextSlot($pReq->target_time_slot));
            $intersect = array_intersect($slotsToCheck, $pSlots);
            if (!empty($intersect)) {
                $recommendations = $this->findAvailableRoomsMultiSlot($room->floor, $day, $slotsToCheck);
                return response()->json([
                    'has_conflict' => true,
                    'type' => 'pending_locked',
                    'message' => "Ruangan {$room->code} pada rentang waktu ini sedang dikunci sementara oleh pengajuan Dosen: {$pReq->lecturer_name}.",
                    'recommendations' => $recommendations,
                ]);
            }
        }

        $durationHours = count($slotsToCheck);
        return response()->json([
            'has_conflict' => false,
            'message' => "Ruangan {$room->code} TERSEDIA dan 100% BEBAS BENTROK untuk durasi {$startSlot} s.d. {$endSlot} WIB ({$durationHours} Jam).",
            'room' => $room,
            'slots_count' => $durationHours,
        ]);
    }

    /**
     * Submit Reschedule with Time Range
     */
    public function submitReschedule(Request $request): JsonResponse
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'target_room_id' => 'required|exists:rooms,id',
            'target_day' => 'required|string',
            'target_time_slot' => 'required|string',
            'target_end_slot' => 'required|string',
            'reason' => 'required|string|min:5',
        ]);

        $schedule = Schedule::with('room')->findOrFail($request->schedule_id);
        $targetRoom = Room::findOrFail($request->target_room_id);

        $slotsToCheck = $this->getSlotsInRange($request->target_time_slot, $request->target_end_slot);
        if (empty($slotsToCheck)) {
            return response()->json(['success' => false, 'message' => 'Rentang jam tidak valid.'], 422);
        }

        if ($targetRoom->status === 'maintenance') {
            return response()->json(['success' => false, 'message' => 'Ruangan target sedang dalam perbaikan.'], 422);
        }

        // Atomic check
        $conflict = Schedule::where('room_id', $targetRoom->id)
            ->where('day', $request->target_day)
            ->where('status', '!=', 'cancelled')
            ->whereIn('time_slot', $slotsToCheck)
            ->exists();

        if ($conflict) {
            return response()->json(['success' => false, 'message' => 'Gagal: Terjadi bentrok jadwal pada rentang jam tersebut.'], 422);
        }

        DB::transaction(function () use ($schedule, $targetRoom, $request) {
            RescheduleRequest::create([
                'schedule_id' => $schedule->id,
                'lecturer_name' => $schedule->lecturer_name,
                'lecturer_code' => $schedule->lecturer_code,
                'course_name' => $schedule->course_name,
                'course_code' => $schedule->course_code,
                'class_group' => $schedule->class_group,
                'from_day' => $schedule->day,
                'from_time_slot' => $schedule->time_slot,
                'from_room_id' => $schedule->room_id,
                'target_day' => $request->target_day,
                'target_time_slot' => $request->target_time_slot,
                'target_end_slot' => $request->target_end_slot,
                'target_room_id' => $targetRoom->id,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            $schedule->update(['status' => 'pending_reschedule']);
        });

        return response()->json([
            'success' => true,
            'message' => "Pengajuan perubahan jadwal ({$request->target_time_slot} s.d. {$request->target_end_slot} WIB) berhasil diajukan ke Admin Gedung GKU 3!",
        ]);
    }

    /**
     * Dosen cancels a class ("Tidak Jadi Kuliah")
     */
    public function cancelClass(Request $request, $id): JsonResponse
    {
        $request->validate([
            'cancellation_reason' => 'required|string|min:5',
        ]);

        $schedule = Schedule::with('room')->findOrFail($id);

        if ($schedule->status === 'cancelled') {
            return response()->json(['success' => false, 'message' => 'Kelas ini sudah dibatalkan sebelumnya.'], 422);
        }

        DB::transaction(function () use ($schedule, $request) {
            $reason = $request->cancellation_reason;

            $schedule->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason,
                'cancelled_at' => now(),
            ]);

            // Save confirmation email to lecturer
            EmailNotification::create([
                'lecturer_name' => $schedule->lecturer_name,
                'lecturer_email' => 'haris.ardian@telkomuniversity.ac.id',
                'type' => 'cancellation',
                'subject' => "[PEMBATALAN BERHASIL] Kelas {$schedule->course_name} Ditiadakan",
                'class_info' => "{$schedule->course_code} - {$schedule->day} {$schedule->time_slot} WIB @ {$schedule->room->code}",
                'body' => "Yth. {$schedule->lecturer_name},\n\nKami mengonfirmasi bahwa perkuliahan Anda untuk mata kuliah {$schedule->course_name} ({$schedule->class_group}) pada hari {$schedule->day} jam {$schedule->time_slot} WIB di ruangan {$schedule->room->code} RESMI DIBATALKAN.\n\nAlasan: {$reason}\n\nRuangan {$schedule->room->code} telah otomatis dibebaskan dan siap digunakan oleh dosen lain yang membutuhkan. Notifikasi pembatalan kelas juga telah diteruskan ke mahasiswa kelas terkait.",
                'status' => 'sent',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "Kelas berhasil DIBATALKAN. Ruang {$schedule->room->code} telah dibebaskan untuk dosen lain, dan email konfirmasi telah dikirimkan ke Anda.",
        ]);
    }

    /**
     * Trigger Simulation of H-1 Reminder Emails
     */
    public function triggerH1Reminders(Request $request): JsonResponse
    {
        // Find lecturer schedules (CSA - Haris Ardian) for upcoming class (e.g. Selasa)
        $schedules = Schedule::with('room')
            ->where('lecturer_code', 'CSA')
            ->where('status', 'confirmed')
            ->get();

        if ($schedules->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada jadwal aktif untuk dikirimi pengingat.'], 422);
        }

        $sentCount = 0;
        foreach ($schedules as $sch) {
            EmailNotification::create([
                'lecturer_name' => $sch->lecturer_name,
                'lecturer_email' => 'haris.ardian@telkomuniversity.ac.id',
                'type' => 'reminder_h1',
                'subject' => "[PENGINGAT KULIAH H-1] Jadwal Mengajar Besok di Gedung GKU 3 ({$sch->day})",
                'class_info' => "{$sch->course_code} - {$sch->day} {$sch->time_slot} WIB @ {$sch->room->code}",
                'body' => "Yth. Bapak {$sch->lecturer_name},\n\nMengingatkan jadwal perkuliahan Anda H-1 besok ({$sch->day}):\n\n- Mata Kuliah : {$sch->course_code} - {$sch->course_name}\n- Waktu Kuliah : {$sch->time_slot} WIB\n- Lokasi Ruang : {$sch->room->code} (Lantai {$sch->room->floor}, GKU 3)\n- Kelas / Mahasiswa : {$sch->class_group} (Kapasitas: {$sch->room->capacity} Kursi)\n\nHarap hadir tepat waktu. Apabila Anda berhalangan hadir atau ingin memindahkan jadwal, mohon segera ajukan pembatalan atau reschedule di Portal Dosen GKU 3 sebelum perkuliahan dimulai agar ruangan tidak terbengkalai dan dapat dimanfaatkan dosen lain.\n\nSalam,\nBagian Logistik & Penjadwalan GKU 3 Telkom University",
                'status' => 'sent',
            ]);
            $sentCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil mengirim {$sentCount} Email Pengingat H-1 ke haris.ardian@telkomuniversity.ac.id! Buka menu 'Kotak Masuk Email' untuk membaca surat pengingat.",
        ]);
    }

    /**
     * Get Lecturer's Email Notifications
     */
    public function getEmails(): JsonResponse
    {
        $emails = EmailNotification::orderBy('created_at', 'desc')->take(20)->get();
        return response()->json(['emails' => $emails]);
    }

    /**
     * Admin: Approve
     */
    public function approveRequest(Request $request, $id): JsonResponse
    {
        $reschedule = RescheduleRequest::with(['schedule', 'targetRoom'])->findOrFail($id);

        if ($reschedule->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Pengajuan sudah diproses sebelumnya.'], 422);
        }

        $slots = $this->getSlotsInRange($reschedule->target_time_slot, $reschedule->target_end_slot ?? $this->getNextSlot($reschedule->target_time_slot));

        $collision = Schedule::where('room_id', $reschedule->target_room_id)
            ->where('day', $reschedule->target_day)
            ->where('status', '!=', 'cancelled')
            ->whereIn('time_slot', $slots)
            ->where('id', '!=', $reschedule->schedule_id)
            ->exists();

        if ($collision) {
            return response()->json(['success' => false, 'message' => 'Gagal approve: Ruangan target telah terisi oleh jadwal lain.'], 422);
        }

        DB::transaction(function () use ($reschedule, $request) {
            $notes = $request->get('admin_notes', 'Disetujui oleh Admin Gedung GKU 3.');

            $reschedule->schedule->update([
                'room_id' => $reschedule->target_room_id,
                'day' => $reschedule->target_day,
                'time_slot' => $reschedule->target_time_slot,
                'status' => 'confirmed',
            ]);

            $reschedule->update([
                'status' => 'approved',
                'admin_notes' => $notes,
            ]);

            // Email confirmation to lecturer
            EmailNotification::create([
                'lecturer_name' => $reschedule->lecturer_name,
                'lecturer_email' => 'haris.ardian@telkomuniversity.ac.id',
                'type' => 'reschedule',
                'subject' => "[DISETUJUI] Pengajuan Reschedule Ruang {$reschedule->targetRoom->code}",
                'class_info' => "{$reschedule->course_code} - {$reschedule->target_day} {$reschedule->target_time_slot} s.d. {$reschedule->target_end_slot} WIB",
                'body' => "Yth. {$reschedule->lecturer_name},\n\nPengajuan pemindahan jadwal Anda untuk mata kuliah {$reschedule->course_name} ke hari {$reschedule->target_day} pukul {$reschedule->target_time_slot} WIB di ruangan {$reschedule->targetRoom->code} telah RESMI DISETUJUI oleh Admin Gedung.\n\nRuangan lama telah dibebaskan dan ruangan baru telah terkunci untuk kelas Anda.\n\nCatatan Admin: {$notes}",
                'status' => 'sent',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "Pengajuan berhasil DISETUJUI. Ruang {$reschedule->targetRoom->code} resmi terkunci dan email konfirmasi telah dikirim ke dosen.",
        ]);
    }

    /**
     * Admin: Reject
     */
    public function rejectRequest(Request $request, $id): JsonResponse
    {
        $request->validate(['reason' => 'required|string']);
        $reschedule = RescheduleRequest::with('schedule')->findOrFail($id);

        if ($reschedule->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Pengajuan sudah diproses sebelumnya.'], 422);
        }

        DB::transaction(function () use ($reschedule, $request) {
            if ($reschedule->schedule) {
                $reschedule->schedule->update(['status' => 'confirmed']);
            }

            $reschedule->update([
                'status' => 'rejected',
                'admin_notes' => $request->reason,
            ]);

            EmailNotification::create([
                'lecturer_name' => $reschedule->lecturer_name,
                'lecturer_email' => 'haris.ardian@telkomuniversity.ac.id',
                'type' => 'reschedule',
                'subject' => "[DITOLAK] Pengajuan Reschedule Ruang {$reschedule->targetRoom->code}",
                'class_info' => "{$reschedule->course_code} - {$reschedule->target_day} {$reschedule->target_time_slot} WIB",
                'body' => "Yth. {$reschedule->lecturer_name},\n\nMohon maaf, pengajuan pemindahan jadwal Anda untuk mata kuliah {$reschedule->course_name} ke ruangan {$reschedule->targetRoom->code} DITOLAK oleh Admin Gedung.\n\nAlasan Penolakan: {$request->reason}\n\nJadwal Anda dikembalikan ke ruangan semula.",
                'status' => 'sent',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "Pengajuan berhasil ditolak dengan alasan: {$request->reason}",
        ]);
    }

    /**
     * Admin: Reroute
     */
    public function rerouteRequest(Request $request, $id): JsonResponse
    {
        $request->validate(['new_target_room_id' => 'required|exists:rooms,id']);
        $reschedule = RescheduleRequest::with('schedule')->findOrFail($id);
        $newRoom = Room::findOrFail($request->new_target_room_id);

        $collision = Schedule::where('room_id', $newRoom->id)
            ->where('day', $reschedule->target_day)
            ->where('status', '!=', 'cancelled')
            ->where('time_slot', $reschedule->target_time_slot)
            ->exists();

        if ($collision || $newRoom->status === 'maintenance') {
            return response()->json(['success' => false, 'message' => 'Ruangan alternatif yang dipilih bentrok atau sedang perbaikan.'], 422);
        }

        DB::transaction(function () use ($reschedule, $newRoom, $request) {
            $notes = $request->get('admin_notes', "Dialihkan oleh Admin ke ruangan {$newRoom->code}.");

            $reschedule->schedule->update([
                'room_id' => $newRoom->id,
                'day' => $reschedule->target_day,
                'time_slot' => $reschedule->target_time_slot,
                'status' => 'confirmed',
            ]);

            $reschedule->update([
                'target_room_id' => $newRoom->id,
                'status' => 'rerouted',
                'admin_notes' => $notes,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "Jadwal berhasil dialihkan (*rerouted*) ke ruang {$newRoom->code} dan disetujui.",
        ]);
    }

    /**
     * Admin: Room Maintenance
     */
    public function toggleMaintenance(Request $request, $roomId): JsonResponse
    {
        $room = Room::findOrFail($roomId);
        if ($room->status === 'active') {
            $reason = $request->get('reason', 'Perbaikan Fasilitas / Kerusakan AC / Proyektor');
            $room->update([
                'status' => 'maintenance',
                'maintenance_reason' => $reason,
            ]);
            $msg = "Ruangan {$room->code} diset ke mode MAINTENANCE ({$reason}).";
        } else {
            $room->update([
                'status' => 'active',
                'maintenance_reason' => null,
            ]);
            $msg = "Ruangan {$room->code} kembali AKTIF.";
        }

        return response()->json(['success' => true, 'message' => $msg, 'room' => $room]);
    }

    /**
     * Calculate all hourly time slots between start and end
     */
    private function getSlotsInRange(string $startTime, string $endTime): array
    {
        $slots = [];
        $recording = false;

        foreach ($this->timeSlots as $slot) {
            if ($slot === $startTime) {
                $recording = true;
            }
            if ($recording) {
                $slots[] = $slot;
            }
            if ($this->getNextSlot($slot) === $endTime) {
                break;
            }
        }

        return $slots;
    }

    private function getNextSlot(string $slot): string
    {
        $idx = array_search($slot, $this->timeSlots);
        if ($idx !== false && isset($this->endTimeSlots[$idx])) {
            return $this->endTimeSlots[$idx];
        }
        return '18:30';
    }

    /**
     * Find available rooms for multi-hour range
     */
    private function findAvailableRoomsMultiSlot(int $baseFloor, string $day, array $slots): array
    {
        $busyRoomIds = Schedule::where('day', $day)
            ->where('status', '!=', 'cancelled')
            ->whereIn('time_slot', $slots)
            ->pluck('room_id')
            ->toArray();

        $pendingRoomIds = RescheduleRequest::where('status', 'pending')
            ->where('target_day', $day)
            ->pluck('target_room_id')
            ->toArray();

        $unavailable = array_unique(array_merge($busyRoomIds, $pendingRoomIds));

        $sameFloorRooms = Room::where('floor', $baseFloor)
            ->where('status', 'active')
            ->whereNotIn('id', $unavailable)
            ->take(3)
            ->get();

        $adjacentFloors = array_filter([$baseFloor - 1, $baseFloor + 1], fn ($f) => $f >= 1 && $f <= 10);
        $adjacentFloorRooms = Room::whereIn('floor', $adjacentFloors)
            ->where('status', 'active')
            ->whereNotIn('id', $unavailable)
            ->take(3)
            ->get();

        return [
            'same_floor' => $sameFloorRooms,
            'adjacent_floor' => $adjacentFloorRooms,
        ];
    }
}
