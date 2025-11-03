<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report - {{ $course->code }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            page-break-after: avoid;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1a202c;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #4a5568;
        }
        .course-info {
            margin-bottom: 20px;
            page-break-after: avoid;
        }
        .course-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .course-info td {
            padding: 5px;
        }
        .course-info td:first-child {
            font-weight: bold;
            width: 150px;
        }
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }
        .attendance-table thead {
            display: table-header-group;
        }
        .attendance-table tbody {
            display: table-row-group;
        }
        .attendance-table th {
            background-color: #4a5568;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #2d3748;
            font-weight: bold;
        }
        .attendance-table td {
            padding: 10px;
            border: 1px solid #cbd5e0;
        }
        .attendance-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .attendance-table thead tr {
            page-break-inside: avoid;
            page-break-after: avoid;
        }
        .attendance-table tr:nth-child(even) {
            background-color: #f7fafc;
        }
        .status-present { color: #22543d; font-weight: bold; }
        .status-absent { color: #742a2a; font-weight: bold; }
        .status-late { color: #744210; font-weight: bold; }
        .status-excused { color: #2c5282; font-weight: bold; }
        .stats {
            margin-top: 20px;
            padding: 15px;
            background-color: #f7fafc;
            border: 1px solid #cbd5e0;
            page-break-inside: avoid;
            page-break-before: auto;
        }
        .stats h3 {
            margin-top: 0;
            color: #2d3748;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        .stat-item {
            padding: 10px;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .stat-label {
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #2d3748;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #718096;
            border-top: 1px solid #cbd5e0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'School Management System') }}</h1>
        <h2>Attendance Report</h2>
    </div>

    <div class="course-info">
        <table>
            <tr>
                <td>Course:</td>
                <td>{{ $course->code }} - {{ $course->name }}</td>
            </tr>
            <tr>
                <td>Date:</td>
                <td>{{ $selectedDate->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td>Report Generated:</td>
                <td>{{ now()->format('F d, Y \a\t g:i A') }}</td>
            </tr>
        </table>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $stats = [
                    'total' => $enrollments->count(),
                    'present' => 0,
                    'absent' => 0,
                    'late' => 0,
                    'excused' => 0,
                    'not_marked' => 0
                ];
            @endphp
            @foreach($enrollments as $enrollment)
                @php
                    $attendance = $enrollment->attendances->first();
                    $status = $attendance ? $attendance->status : 'not_marked';
                    $stats[$status]++;
                @endphp
                <tr>
                    <td>{{ $enrollment->student->name }}</td>
                    <td>{{ $enrollment->student->email }}</td>
                    <td>
                        @if($status === 'present')
                            <span class="status-present">Present</span>
                        @elseif($status === 'absent')
                            <span class="status-absent">Absent</span>
                        @elseif($status === 'late')
                            <span class="status-late">Late</span>
                        @elseif($status === 'excused')
                            <span class="status-excused">Excused</span>
                        @else
                            <span style="color: #a0aec0;">Not Marked</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="stats">
        <h3>Summary Statistics</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-label">Total Students</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Present</div>
                <div class="stat-value" style="color: #22543d;">{{ $stats['present'] }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Absent</div>
                <div class="stat-value" style="color: #742a2a;">{{ $stats['absent'] }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Late</div>
                <div class="stat-value" style="color: #744210;">{{ $stats['late'] }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Excused</div>
                <div class="stat-value" style="color: #2c5282;">{{ $stats['excused'] }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Not Marked</div>
                <div class="stat-value" style="color: #a0aec0;">{{ $stats['not_marked'] }}</div>
            </div>
        </div>
        @if($stats['total'] > 0)
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                <strong>Attendance Rate:</strong> 
                {{ number_format((($stats['present'] + $stats['late']) / $stats['total']) * 100, 2) }}%
            </div>
        @endif
    </div>

    <div class="footer">
        <p>{{ config('app.name', 'School Management System') }}</p>
        <p>{{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>

