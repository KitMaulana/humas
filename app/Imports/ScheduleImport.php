<?php

namespace App\Imports;

use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;

class ScheduleImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Temukan atau Buat Kelas
        $class = SchoolClass::firstOrCreate(
            ['name' => $row['nama_kelas']],
            [
                'grade' => $this->detectGrade($row['nama_kelas']),
                'slug' => \Str::slug($row['nama_kelas'])
            ]
        );

        // Temukan atau Buat Guru
        $teacher = Teacher::firstOrCreate(
            ['name' => $row['nama_guru']],
            ['status' => 'Data Import']
        );

        // Temukan atau Buat Mapel
        $subject = Subject::firstOrCreate(
            ['name' => $row['mata_pelajaran']],
            ['slug' => \Str::slug($row['mata_pelajaran'])]
        );

        // Parsing waktu: kadang Excel merubah jam menjadi float fraction
        $startTime = $this->parseTime($row['jam_mulai']);
        $endTime = $this->parseTime($row['jam_selesai']);

        return new Schedule([
            'school_class_id' => $class->id,
            'teacher_id'      => $teacher->id,
            'subject_id'      => $subject->id,
            'day'             => $row['hari'],
            'start_time'      => $startTime,
            'end_time'        => $endTime,
        ]);
    }

    private function detectGrade($className)
    {
        $className = strtoupper($className);
        if (str_starts_with($className, 'XII')) return 'XII';
        if (str_starts_with($className, 'XI')) return 'XI';
        if (str_starts_with($className, 'X')) return 'X';
        return 'X';
    }

    private function parseTime($value)
    {
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('H:i:s');
        }
        return date('H:i:s', strtotime($value));
    }
}
