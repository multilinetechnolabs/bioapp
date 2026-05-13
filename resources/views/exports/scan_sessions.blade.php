<table border="1">
    <thead>
    </thead>
    <tbody>
        <tr>
            <td>Scan Date: {{ \Carbon\Carbon::create($scan_session->date_started)->format('F j, Y') }}</td>
        </tr>
        <tr>
            <td>Scan Type: {{ ucwords(str_replace('_', ' ', $scan_session->scan_type)) }}</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td>Session Type: {{ ucwords($scan_session->cost_type) }}</td>
        </tr>
        <tr>
            <td>Session Cost: ${{ number_format($scan_session->cost, 2) }}</td>
        </tr>
    </tbody>
</table>
<table border="1">
    <thead>
    </thead>
    <tbody>
        <tr>
            <td><b>Client Information</b></td>
        </tr>
        <tr>
            <td>Name: {{ ucwords($scan_session->client->name) }}</td>
        </tr>
        <tr>
            <td>Age: {{ $scan_session->client->age }}</td>
        </tr>
        <tr>
            <td>Gender: {{ ucwords($scan_session->client->gender) }}</td>
        </tr>
        <tr>
            <td>Address: {{ $scan_session->client->address }}</td>
        </tr>
    </tbody>
</table>
<table border="1">
    <thead>
        <tr>
            <th>POINT/NAME</th>
            <th>RADICAL</th>
            <th>START/ORIGIN</th>
            <th>LEADS/SYMPTOMS</th>
            <th>PATH/ROUTE/CAUSE AND EFFECT</th>
            <th>ALTERNATIVE ROUTES</th>
        </tr>
    </thead>
    <tbody>
        @foreach($scan_session->pairs as $pair)
            <tr>
                <td>{{ $pair->name }}</td>
                <td>{{ $pair->radical }}</td>
                <td>{{ $pair->origins }}</td>
                <td>{{ $pair->symptoms }}</td>
                <td>{{ $pair->paths }}</td>
                <td>{{ $pair->alternative_routes }}</td>
            </tr>
        @endforeach
    </tbody>
</table>