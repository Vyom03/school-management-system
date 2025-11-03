<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Grades Report - {{ $course->code }}</title>
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
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }
        .grades-table thead {
            display: table-header-group;
        }
        .grades-table tbody {
            display: table-row-group;
        }
        .grades-table th {
            background-color: #4a5568;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #2d3748;
            font-weight: bold;
        }
        .grades-table td {
            padding: 10px;
            border: 1px solid #cbd5e0;
        }
        .grades-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .grades-table thead tr {
            page-break-inside: avoid;
            page-break-after: avoid;
        }
        .grades-table tr:nth-child(even) {
            background-color: #f7fafc;
        }
        .letter-grade {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .grade-A { background-color: #c6f6d5; color: #22543d; }
        .grade-B { background-color: #bee3f8; color: #2c5282; }
        .grade-C { background-color: #faf089; color: #744210; }
        .grade-D { background-color: #fed7aa; color: #7c2d12; }
        .grade-F { background-color: #feb2b2; color: #742a2a; }
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
        <h2>Grades Report</h2>
    </div>

    <div class="course-info">
        <table>
            <tr>
                <td>Course:</td>
                <td>{{ $course->code }} - {{ $course->name }}</td>
            </tr>
            @if($course->teacher)
                <tr>
                    <td>Instructor:</td>
                    <td>{{ $course->teacher->name }}</td>
                </tr>
            @endif
            <tr>
                <td>Report Generated:</td>
                <td>{{ now()->format('F d, Y \a\t g:i A') }}</td>
            </tr>
        </table>
    </div>

    <table class="grades-table">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Average Grade</th>
                <th>Letter Grade</th>
                <th>Total Assignments</th>
            </tr>
        </thead>
        <tbody>
            @php
                $averages = [];
                $stats = [
                    'total_students' => $enrollments->count(),
                    'with_grades' => 0,
                    'average' => 0,
                    'highest' => 0,
                    'lowest' => 0
                ];
            @endphp
            @foreach($enrollments as $enrollment)
                @php
                    $avg = $enrollment->averageGrade();
                    if ($avg > 0) {
                        $averages[] = $avg;
                        $stats['with_grades']++;
                    }
                @endphp
                <tr>
                    <td>{{ $enrollment->student->name }}</td>
                    <td>{{ $enrollment->student->email }}</td>
                    <td>
                        @if($avg > 0)
                            {{ number_format($avg, 2) }}%
                        @else
                            <span style="color: #a0aec0;">No grades</span>
                        @endif
                    </td>
                    <td>
                        @if($avg > 0)
                            <span class="letter-grade grade-{{ $enrollment->letterGrade() }}">
                                {{ $enrollment->letterGrade() }}
                            </span>
                        @else
                            <span style="color: #a0aec0;">-</span>
                        @endif
                    </td>
                    <td>{{ $enrollment->grades->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if(count($averages) > 0)
        @php
            $stats['average'] = round(array_sum($averages) / count($averages), 2);
            $stats['highest'] = round(max($averages), 2);
            $stats['lowest'] = round(min($averages), 2);
        @endphp
        <div class="stats">
            <h3>Course Statistics</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-label">Total Students</div>
                    <div class="stat-value">{{ $stats['total_students'] }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Students with Grades</div>
                    <div class="stat-value">{{ $stats['with_grades'] }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Average Grade</div>
                    <div class="stat-value">{{ $stats['average'] }}%</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Highest Grade</div>
                    <div class="stat-value" style="color: #22543d;">{{ $stats['highest'] }}%</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Lowest Grade</div>
                    <div class="stat-value" style="color: #742a2a;">{{ $stats['lowest'] }}%</div>
                </div>
            </div>
        </div>
    @endif

    <div class="footer">
        <p>{{ config('app.name', 'School Management System') }}</p>
        <p>{{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>

