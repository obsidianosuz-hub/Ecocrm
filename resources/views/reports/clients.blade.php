<!DOCTYPE html>
<html>
<head>
    <title>Clients Export</title>
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
        <h1>MIJOZLAR BAZASI</h1>
        <p>Obsidian OS System Export | {{ now()->format('d.m.Y H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>F.I.O</th>
                <th>Telefon</th>
                <th>Manzil</th>
                <th>Ro'yxatdan o'tgan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                <td>{{ $client->phone }}</td>
                <td>{{ $client->address }}</td>
                <td>{{ $client->created_at->format('d.m.Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
