<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Schedule;
use App\Models\RescheduleRequest;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create 200 Rooms: 10 floors, 20 rooms each
        $rooms = [];
        for ($floor = 1; $floor <= 10; $floor++) {
            $floorStr = str_pad($floor, 2, '0', STR_PAD_LEFT);
            for ($num = 1; $num <= 20; $num++) {
                $numStr = str_pad($num, 2, '0', STR_PAD_LEFT);
                $code = "GKU3-{$floorStr}-{$numStr}";
                
                $status = 'active';
                $reason = null;

                // Example maintenance for realistic testing
                if ($floor === 1 && $num === 4) {
                    $status = 'maintenance';
                    $reason = 'AC Mengalami Kebocoran & Perbaikan Sound System';
                } elseif ($floor === 2 && $num === 12) {
                    $status = 'maintenance';
                    $reason = 'Perbaikan Proyektor HDMI & Lampu Ruangan';
                }

                $rooms["{$floor}-{$num}"] = Room::create([
                    'code' => $code,
                    'building' => 'GKU 3',
                    'floor' => $floor,
                    'room_number' => $num,
                    'capacity' => 45 + ($num % 3) * 5, // 45 to 55 capacity
                    'status' => $status,
                    'maintenance_reason' => $reason,
                ]);
            }
        }

        // Realistic schedule data
        $courseCatalog = [
            ['code' => 'BBK1AAB4', 'name' => 'ALGORITMA DAN PEMROGRAMAN', 'lecturer_code' => 'ENA', 'lecturer_name' => 'Dr. Ely Nur Aziza, S.T., M.T.', 'class' => 'BS1SI-50-REG-11'],
            ['code' => 'BBK1CAB3', 'name' => 'MATEMATIKA UNTUK SISTEM INFORMASI', 'lecturer_code' => 'CSA', 'lecturer_name' => 'Haris Ardian, M.Kom.', 'class' => 'BS1SI-50-REG-11'],
            ['code' => 'BBK1DAB3', 'name' => 'PENGANTAR SISTEM INFORMASI', 'lecturer_code' => 'HNH', 'lecturer_name' => 'Hani Nurhasanah, S.T., M.Kom.', 'class' => 'BS1SI-50-REG-11'],
            ['code' => 'BBK1BAB3', 'name' => 'MATEMATIKA DISKRIT', 'lecturer_code' => 'RAP', 'lecturer_name' => 'Rangga Aditya, M.Sc.', 'class' => 'BS1SI-50-REG-11'],
            ['code' => 'BBK1EAB3', 'name' => 'SISTEM ENTERPRISE', 'lecturer_code' => 'RAC', 'lecturer_name' => 'Rachmawati, S.Kom., M.T.', 'class' => 'BS1SI-50-REG-11'],
            ['code' => 'UCK1FDB1', 'name' => 'INTERNALISASI BUDAYA & KARAKTER', 'lecturer_code' => 'YAP', 'lecturer_name' => 'Yudi A. P., M.Pd.', 'class' => 'BS1SI-50-REG-11'],
            ['code' => 'UAKXACB2', 'name' => 'AGAMA ISLAM', 'lecturer_code' => 'UFH', 'lecturer_name' => 'Ustadz Fathurrahman, M.Ag.', 'class' => 'BS1SI-50-REG-11'],
            ['code' => 'DII1AAB2', 'name' => 'JARINGAN KOMPUTER & CLOUD', 'lecturer_code' => 'MHD', 'lecturer_name' => 'Mahmud Hidayat, M.T.', 'class' => 'IF-48-INT-02'],
            ['code' => 'CSH2CAB3', 'name' => 'STRUKTUR DATA & ANALISIS', 'lecturer_code' => 'BRY', 'lecturer_name' => 'Dr. Bryan Santoso, Ph.D.', 'class' => 'IF-48-03'],
            ['code' => 'SE201A', 'name' => 'REKAYASA PERANGKAT LUNAK', 'lecturer_code' => 'DNI', 'lecturer_name' => 'Denny Irawan, M.Kom.', 'class' => 'RPL-47-01'],
        ];

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $timeSlots = ['06:30', '07:30', '08:30', '09:30', '10:30', '11:30', '12:30', '13:30', '14:30', '15:30', '16:30', '17:30', '18:30'];

        // Populate base schedules across floors
        foreach ($days as $day) {
            for ($floor = 1; $floor <= 10; $floor++) {
                // Populate 4 to 8 rooms per floor with multi-hour blocks
                for ($roomNum = 1; $roomNum <= 20; $roomNum += 3) {
                    $room = $rooms["{$floor}-{$roomNum}"];
                    if ($room->status === 'maintenance') continue;

                    // Slot pagi (08:30 - 10:30)
                    $c1 = $courseCatalog[($floor + $roomNum) % count($courseCatalog)];
                    Schedule::create([
                        'room_id' => $room->id,
                        'day' => $day,
                        'time_slot' => '08:30',
                        'course_code' => $c1['code'],
                        'course_name' => $c1['name'],
                        'lecturer_code' => $c1['lecturer_code'],
                        'lecturer_name' => $c1['lecturer_name'],
                        'class_group' => $c1['class'],
                        'status' => 'confirmed',
                    ]);
                    Schedule::create([
                        'room_id' => $room->id,
                        'day' => $day,
                        'time_slot' => '09:30',
                        'course_code' => $c1['code'],
                        'course_name' => $c1['name'],
                        'lecturer_code' => $c1['lecturer_code'],
                        'lecturer_name' => $c1['lecturer_name'],
                        'class_group' => $c1['class'],
                        'status' => 'confirmed',
                    ]);

                    // Slot siang (13:30 - 15:30)
                    $c2 = $courseCatalog[($floor + $roomNum + 3) % count($courseCatalog)];
                    Schedule::create([
                        'room_id' => $room->id,
                        'day' => $day,
                        'time_slot' => '13:30',
                        'course_code' => $c2['code'],
                        'course_name' => $c2['name'],
                        'lecturer_code' => $c2['lecturer_code'],
                        'lecturer_name' => $c2['lecturer_name'],
                        'class_group' => $c2['class'],
                        'status' => 'confirmed',
                    ]);
                    Schedule::create([
                        'room_id' => $room->id,
                        'day' => $day,
                        'time_slot' => '14:30',
                        'course_code' => $c2['code'],
                        'course_name' => $c2['name'],
                        'lecturer_code' => $c2['lecturer_code'],
                        'lecturer_name' => $c2['lecturer_name'],
                        'class_group' => $c2['class'],
                        'status' => 'confirmed',
                    ]);
                }
            }
        }

        // Add a specific schedule for Haris Ardian (CSA) on Selasa 09:30 at GKU3-01-02
        $roomTarget = $rooms['1-2'];
        $csaSchedule = Schedule::where('room_id', $roomTarget->id)
            ->where('day', 'Selasa')
            ->where('time_slot', '09:30')
            ->first();

        if ($csaSchedule) {
            $csaSchedule->update([
                'course_code' => 'BBK1CAB3',
                'course_name' => 'MATEMATIKA UNTUK SISTEM INFORMASI',
                'lecturer_code' => 'CSA',
                'lecturer_name' => 'Haris Ardian, M.Kom.',
                'class_group' => 'BS1SI-50-REG-11',
                'status' => 'pending_reschedule',
            ]);
        } else {
            $csaSchedule = Schedule::create([
                'room_id' => $roomTarget->id,
                'day' => 'Selasa',
                'time_slot' => '09:30',
                'course_code' => 'BBK1CAB3',
                'course_name' => 'MATEMATIKA UNTUK SISTEM INFORMASI',
                'lecturer_code' => 'CSA',
                'lecturer_name' => 'Haris Ardian, M.Kom.',
                'class_group' => 'BS1SI-50-REG-11',
                'status' => 'pending_reschedule',
            ]);
        }

        // Create sample pending reschedule request from Haris Ardian
        RescheduleRequest::create([
            'schedule_id' => $csaSchedule->id,
            'lecturer_name' => 'Haris Ardian, M.Kom.',
            'lecturer_code' => 'CSA',
            'course_name' => 'MATEMATIKA UNTUK SISTEM INFORMASI',
            'course_code' => 'BBK1CAB3',
            'class_group' => 'BS1SI-50-REG-11',
            'from_day' => 'Selasa',
            'from_time_slot' => '09:30',
            'from_room_id' => $roomTarget->id,
            'target_day' => 'Senin',
            'target_time_slot' => '10:30',
            'target_room_id' => $rooms['1-5']->id,
            'reason' => 'Ada bentrok jadwal mengajar di kampus lain pada hari Selasa, diajukan maju ke hari Senin jam 10:30 di GKU3-01-05.',
            'status' => 'pending',
            'admin_notes' => null,
        ]);
    }
}
