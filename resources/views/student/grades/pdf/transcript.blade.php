<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Academic Transcript</title>
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
        .student-info {
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
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: auto;
        }
        .grades-table thead {
            display: table-header-group;
        }
        .grades-table tbody {
            display: table-row-group;
        }
        .grades-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .grades-table thead tr {
            page-break-inside: avoid;
            page-break-after: avoid;
        }
        .grades-table th {
            background-color: #e2e8f0;
            padding: 8px;
            text-align: left;
            border: 1px solid #cbd5e0;
            font-weight: bold;
        }
        .grades-table td {
            padding: 8px;
            border: 1px solid #cbd5e0;
        }
        .grades-table tr:nth-child(even) {
            background-color: #f7fafc;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f7fafc;
            border: 1px solid #cbd5e0;
            page-break-inside: avoid;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            padding: 5px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #718096;
            border-top: 1px solid #cbd5e0;
            padding-top: 10px;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'School Management System') }}</h1>
        <h2>Academic Transcript</h2>
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

            @if($enrollment->grades->count() > 0)
                <table class="grades-table">
                    <thead>
                        <tr>
                            <th>Assignment</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Letter Grade</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollment->grades as $grade)
                            <tr>
                                <td>{{ $grade->assignment_name }}</td>
                                <td>{{ $grade->score }} / {{ $grade->max_score }}</td>
                                <td>{{ number_format($grade->percentage, 2) }}%</td>
                                <td>
                                    <span class="letter-grade grade-{{ $grade->letter_grade }}">
                                        {{ $grade->letter_grade }}
                                    </span>
                                </td>
                                <td>{{ $grade->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="summary">
                    <div class="summary-row">
                        <span><strong>Course Average:</strong></span>
                        <span><strong>{{ number_format($enrollment->averageGrade(), 2) }}% ({{ $enrollment->letterGrade() }})</strong></span>
                    </div>
                </div>
            @else
                <p style="padding: 10px; color: #718096;">No grades recorded for this course.</p>
            @endif
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

