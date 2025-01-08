<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>
    <h2>Transactions Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Commission</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ ucfirst($transaction->type) }}</td>
                    <td>{{ $transaction->category->name ?? '-' }}</td>
                    <td>{{ $transaction->amount }}</td>
                    <td>{{ $transaction->commission->amount ?? '-' }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h3>Report Summary</h3>
    <ul>
        <li>Total Income: {{ number_format($totalIncome, 2) }}</li>
        <li>Total Expense: {{ number_format($totalExpense, 2) }}</li>
        <li>Total Commission: {{ number_format($totalCommission, 2) }}</li>
        <li>Net Available Balance: {{ number_format($netAmount, 2) }}</li>
    </ul>

</body>

</html>
