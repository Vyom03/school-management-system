<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Parent Registration Codes - {{ $gradeName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 22px;
            color: #1a202c;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 15px;
            color: #4a5568;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 9px;
            color: #9ca3af;
            margin: 3px 0;
        }
        .info-box {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 12px;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 8px;
            font-size: 10px;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 160px;
            color: #4a5568;
        }
        .instructions {
            background-color: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 12px;
            margin-bottom: 20px;
        }
        .instructions h3 {
            color: #1e40af;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .instructions ul {
            margin: 8px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 4px 0;
            font-size: 10px;
        }
        .warning-box {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 10px;
            margin-bottom: 15px;
        }
        .warning-box strong {
            color: #92400e;
            font-size: 10px;
        }
        .warning-box ul {
            margin: 5px 0 0 0;
            padding-left: 20px;
            font-size: 9px;
        }
        .codes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .codes-table thead {
            background-color: #2563eb;
            color: white;
        }
        .codes-table th {
            padding: 10px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            border: 1px solid #1e40af;
        }
        .codes-table td {
            padding: 8px 6px;
            border: 1px solid #d1d5db;
            font-size: 9px;
        }
        .codes-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .code-cell {
            font-family: Courier New, monospace;
            font-weight: bold;
            font-size: 11px;
            color: #1e40af;
            letter-spacing: 1px;
        }
        .student-name {
            font-weight: bold;
            color: #1a202c;
        }
        .footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #d1d5db;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ config('app.name', 'School Management System') }}</h1>
        <h2>Parent Portal Registration Codes</h2>
        <p>{{ $gradeName }}</p>
        <p>Generated on {{ now()->format('F d, Y') }}</p>
    </div>

    <!-- Information Box -->
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td>Grade Level:</td>
                <td><strong>{{ $gradeName }}</strong></td>
            </tr>
            <tr>
                <td>Total Students:</td>
                <td><strong>{{ $totalStudents }}</strong></td>
            </tr>
            <tr>
                <td>Total Codes Generated:</td>
                <td><strong>{{ $totalCodes }}</strong></td>
            </tr>
            <tr>
                <td>Codes Per Student:</td>
                <td><strong>{{ $codesPerStudent }}</strong></td>
            </tr>
            <tr>
                <td>Relationship:</td>
                <td><strong>{{ ucfirst($relationship) }}</strong></td>
            </tr>
            <tr>
                <td>Expiration Date:</td>
                <td><strong>{{ $expiresAt->format('F d, Y') }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Instructions -->
    <div class="instructions">
        <h3>Instructions for Parents</h3>
        <ul>
            <li>Each student has <strong>{{ $codesPerStudent }}</strong> unique registration code(s) listed below</li>
            <li>Visit the school website and go to the <strong>Parent Portal Login</strong> page</li>
            <li>Click on <strong>"Create Parent Account"</strong></li>
            <li>Enter the registration code provided for your child</li>
            <li>Complete the registration form with your details</li>
            <li>After registration, you will be able to view your child's grades, attendance, and fees</li>
        </ul>
    </div>

    <!-- Warning Box -->
    <div class="warning-box">
        <strong>Important:</strong>
        <ul>
            <li>Each code can only be used <strong>once</strong></li>
            <li>Codes will expire on <strong>{{ $expiresAt->format('F d, Y') }}</strong></li>
            <li>Please keep these codes secure and do not share them publicly</li>
        </ul>
    </div>

    <!-- Codes Table -->
    <table class="codes-table">
        <thead>
            <tr>
                <th style="width: 25%;">Student Name</th>
                <th style="width: 25%;">Student Email</th>
                <th style="width: 30%;">Registration Code(s)</th>
                <th style="width: 10%;">Relationship</th>
                <th style="width: 10%;">Expires</th>
            </tr>
        </thead>
        <tbody>
            @if(count($pdfData) > 0)
                @foreach($pdfData as $row)
                    <tr>
                        <td class="student-name">{{ $row['student_name'] }}</td>
                        <td>{{ $row['student_email'] }}</td>
                        <td class="code-cell">{{ $row['codes'] }}</td>
                        <td>{{ ucfirst($relationship) }}</td>
                        <td>{{ $expiresAt->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">No codes available</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>This document contains sensitive registration codes. Please distribute securely to authorized parents only.</p>
        <p>{{ config('app.name', 'School Management System') }} | Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
