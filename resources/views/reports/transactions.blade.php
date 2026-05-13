<!DOCTYPE html>
<html>
<head>
    <title>Transactions Export</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FCC HUB - TRANZAKSIYALAR</h1>
        <p>Obsidian OS System Export | {{ now()->format('d.m.Y H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Sana</th>
                <th>Turi</th>
                <th>Kassir/Hodim</th>
                <th>Tavsif</th>
                <th>Summa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $tx)
            <tr>
                <td>{{ $tx->created_at->format('d.m.Y H:i') }}</td>
                <td>{{ strtoupper($tx->type) }}</td>
                <td>{{ $tx->user->name }}</td>
                <td>{{ $tx->description }}</td>
                <td>{{ number_format($tx->amount, 0) }} UZS</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
