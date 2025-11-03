<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Grades Overview Report</title>
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
        <h2>Grades Overview Report</h2>
    </div>

    <div class="report-info">
        <table>
            <tr>
                <td>Report Date:</td>
                <td>{{ now()->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td>Generated:</td>
                <td>{{ now()->format('F d, Y \a\t g:i A') }}</td>
            </tr>
        </table>
    </div>

    <table class="grades-table">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Students</th>
                <th>Average Grade</th>
                <th>Highest</th>
                <th>Lowest</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gradeData as $data)
                <tr>
                    <td>{{ $data['course']->code }}</td>
                    <td>{{ $data['course']->name }}</td>
                    <td>{{ $data['students'] }}</td>
                    <td>
                        @if($data['average'] > 0)
                            {{ number_format($data['average'], 2) }}%
                        @else
                            <span style="color: #a0aec0;">No grades</span>
                        @endif
                    </td>
                    <td>
                        @if($data['highest'] > 0)
                            <span style="color: #22543d; font-weight: bold;">{{ number_format($data['highest'], 2) }}%</span>
                        @else
                            <span style="color: #a0aec0;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($data['lowest'] > 0)
                            <span style="color: #742a2a; font-weight: bold;">{{ number_format($data['lowest'], 2) }}%</span>
                        @else
                            <span style="color: #a0aec0;">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($gradeData->count() === 0)
        <p style="padding: 20px; text-align: center; color: #718096;">No grade data available.</p>
    @endif

    <div class="footer">
        <p>{{ config('app.name', 'School Management System') }}</p>
        <p>{{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>

