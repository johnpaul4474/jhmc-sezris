<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
</head>
<body style="font-family: DejaVu Sans;">
<div style="padding: 20px;">

    <h1>{{ $title }}</h1>

    <img src="{{ public_path('storage/loctr/T67wirLP4lcwOKlYZlLOaS8OUgDGppKwKgFxudVL.png') }}"
         width="100" height="auto">

    <p>{{ $message }}</p>

    <table style="width:100%; border-collapse: collapse; font-family: Arial, sans-serif;">
        <thead>
            <tr>
                <th style="border: 1px solid #000; padding: 8px; background: #f2f2f2;">#</th>
                <th style="border: 1px solid #000; padding: 8px; background: #f2f2f2;">Name</th>
                <th style="border: 1px solid #000; padding: 8px; background: #f2f2f2;">Email</th>
                <th style="border: 1px solid #000; padding: 8px; background: #f2f2f2;">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">1</td>
                <td style="border: 1px solid #000; padding: 8px;">Juan Dela Cruz</td>
                <td style="border: 1px solid #000; padding: 8px;">juan@example.com</td>
                <td style="border: 1px solid #000; padding: 8px;">Approved</td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">2</td>
                <td style="border: 1px solid #000; padding: 8px;">Maria Santos</td>
                <td style="border: 1px solid #000; padding: 8px;">maria@example.com</td>
                <td style="border: 1px solid #000; padding: 8px;">Pending</td>
            </tr>
        </tbody>
    </table>

</div>
</body>
</html>
