<!DOCTYPE html>
<html>
<head>
    <title>Employee List</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; }
    </style>
</head>
<body>
    <h2>Employee List (සේවක ලැයිස්තුව)</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Designation</th>
                <th>Salary (Rs)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
            <tr>
                <td>{{ $employee->id }}</td>
                <td>{{ $employee->full_name }}</td>
                <td>{{ $employee->designation }}</td>
                <td>{{ number_format($employee->salary, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;">No Employees Found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
