<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report - {{ $student->name }}</title>
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
        .student-info {
            margin-bottom: 25px;
            page-break-after: avoid;
        }
        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .student-info td {
            padding: 5px;
        }
        .student-info td:first-child {
            font-weight: bold;
            width: 150px;
        }
        .course-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .course-header {
            background-color: #4a5568;
            color: white;
            padding: 10px;
            font-weight: bold;
            margin-bottom: 10px;
            page-break-after: avoid;
            page-break-inside: avoid;
        }
        .stats {
            margin: 15px 0;
            padding: 15px;
            background-color: #f7fafc;
            border: 1px solid #cbd5e0;
            page-break-inside: avoid;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        .stat-item {
            padding: 10px;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            text-align: center;
        }
        .stat-label {
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #2d3748;
            margin-top: 5px;
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

    <div class="student-info">
        <table>
            <tr>
                <td>Student Name:</td>
                <td>{{ $student->name }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{ $student->email }}</td>
            </tr>
            <tr>
                <td>Report Date:</td>
                <td>{{ now()->format('F d, Y') }}</td>
            </tr>
        </table>
    </div>

    @foreach($enrollments as $enrollment)
        <div class="course-section">
            <div class="course-header">
                {{ $enrollment->course->code }} - {{ $enrollment->course->name }}
                @if($enrollment->course->teacher)
                    | Instructor: {{ $enrollment->course->teacher->name }}
                @endif
            </div>

            <div class="stats">
                <h3 style="margin-top: 0; color: #2d3748;">Attendance Statistics</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-label">Total Records</div>
                        <div class="stat-value">{{ $enrollment->attendance_stats['total'] }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Present</div>
                        <div class="stat-value" style="color: #22543d;">{{ $enrollment->attendance_stats['present'] }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Absent</div>
                        <div class="stat-value" style="color: #742a2a;">{{ $enrollment->attendance_stats['absent'] }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Late</div>
                        <div class="stat-value" style="color: #744210;">{{ $enrollment->attendance_stats['late'] }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Excused</div>
                        <div class="stat-value" style="color: #2c5282;">{{ $enrollment->attendance_stats['excused'] }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Attendance Rate</div>
                        <div class="stat-value" style="color: #22543d;">{{ number_format($enrollment->attendance_stats['percentage'], 2) }}%</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if($enrollments->count() === 0)
        <p style="padding: 20px; text-align: center; color: #718096;">No enrollments found.</p>
    @endif

    <div class="footer">
        <p>{{ config('app.name', 'School Management System') }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>

