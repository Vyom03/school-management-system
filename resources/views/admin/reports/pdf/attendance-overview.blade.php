<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Overview Report</title>
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
        .report-info {
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
        .report-info {
            margin-bottom: 20px;
            page-break-after: avoid;
        }
        .report-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .report-info td {
            padding: 5px;
        }
        .report-info td:first-child {
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
        .percentage {
            font-weight: bold;
        }
        .percentage-high { color: #22543d; }
        .percentage-medium { color: #744210; }
        .percentage-low { color: #742a2a; }
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
        <h2>Attendance Overview Report</h2>
    </div>

    <div class="report-info">
        <table>
            <tr>
                <td>Report Period:</td>
                <td>{{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td>Generated:</td>
                <td>{{ now()->format('F d, Y \a\t g:i A') }}</td>
            </tr>
        </table>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Total Records</th>
                <th>Present</th>
                <th>Attendance %</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceData as $data)
                @php
                    $percentage = $data['percentage'];
                    $percentageClass = $percentage >= 80 ? 'percentage-high' : ($percentage >= 60 ? 'percentage-medium' : 'percentage-low');
                @endphp
                <tr>
                    <td>{{ $data['course']->code }}</td>
                    <td>{{ $data['course']->name }}</td>
                    <td>{{ $data['total'] }}</td>
                    <td>{{ $data['present'] }}</td>
                    <td>
                        <span class="percentage {{ $percentageClass }}">
                            {{ number_format($percentage, 2) }}%
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($attendanceData->count() === 0)
        <p style="padding: 20px; text-align: center; color: #718096;">No attendance data available.</p>
    @endif

    <div class="footer">
        <p>{{ config('app.name', 'School Management System') }}</p>
        <p>{{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>

