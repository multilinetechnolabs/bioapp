<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ env('APP_TITLE') }} | Scan Session Print</title>
        <style>
            body {
                color: #0f172a;
                font-family: Arial, Helvetica, sans-serif;
                margin: 24px;
            }

            h1,
            h2 {
                margin-bottom: 8px;
            }

            .print-meta {
                color: #475569;
                margin-bottom: 18px;
            }

            .print-section {
                margin-bottom: 24px;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            th,
            td {
                border: 1px solid #cbd5e1;
                padding: 10px 12px;
                text-align: left;
                vertical-align: top;
            }

            th {
                background: #f8fafc;
            }

            .label-cell {
                background: #f8fafc;
                font-weight: 700;
                width: 220px;
            }

            @media print {
                body {
                    margin: 0;
                }
            }
        </style>
    </head>
    <body>
        <div class="print-section">
            <h1>Scan Session</h1>
            <div class="print-meta">{{ ucwords(str_replace('_', ' ', $scanSession->scan_type)) }} | {{ $scanSession->date_started }}</div>
        </div>

        <div class="print-section">
            <h2>Session Details</h2>
            <table>
                <tbody>
                    <tr>
                        <td class="label-cell">Client Name</td>
                        <td>{{ $scanSession->client->name }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Date Started</td>
                        <td>{{ $scanSession->date_started }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Date Ended</td>
                        <td>{{ $scanSession->date_ended ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Session Type</td>
                        <td>{{ $scanSession->cost_type ? ucwords($scanSession->cost_type) : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Session Cost</td>
                        <td>
                            @if (!is_null($scanSession->cost))
                                ${{ number_format($scanSession->cost, 2) }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="print-section">
            <h2>Client Information</h2>
            <table>
                <tbody>
                    <tr>
                        <td class="label-cell">Age</td>
                        <td>{{ $scanSession->client->age ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Gender</td>
                        <td>{{ $scanSession->client->gender ? ucwords($scanSession->client->gender) : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Address</td>
                        <td>{{ $scanSession->client->address ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="print-section">
            <h2>Pairs</h2>
            <table>
                <thead>
                    <tr>
                        <th>Point/Name</th>
                        <th>Radical</th>
                        <th>Start/Origin</th>
                        <th>Leads/Symptoms</th>
                        <th>Path/Route/Cause and Effect</th>
                        <th>Alternative Routes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($scanSession->pairs as $pair)
                        <tr>
                            <td>{{ $pair->name }}</td>
                            <td>{{ $pair->radical }}</td>
                            <td>{{ $pair->origins }}</td>
                            <td>{{ $pair->symptoms }}</td>
                            <td>{{ $pair->paths }}</td>
                            <td>{{ $pair->alternative_routes }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No saved pairs for this session.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <script>
            window.addEventListener('load', function () {
                window.print();
            });
        </script>
    </body>
</html>
