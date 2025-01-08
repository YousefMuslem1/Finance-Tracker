@extends('layouts.app')

@section('content')
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row">
        <!-- Total Income -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Total Income</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">{{ $totalIncome }} USD</span>
                </div>
            </div>
        </div>
        <!-- Total Expense -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">Total Expense</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">{{ $totalExpense }} USD</span>
                </div>
            </div>
        </div>
        <!-- Total Commission -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Total Commission</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">{{ $totalCommission }} USD</span>
                </div>
            </div>
        </div>
        <!-- Net Amount -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Net Amount</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">{{ $netAmount }} USD</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Income and Expense Chart -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Income and Expense Chart
                </div>
                <div class="card-body">
                    <canvas id="myAreaChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <!-- Latest Transactions -->
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-clock me-1"></i>
                    Latest Transactions
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($latestTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at }}</td>
                                    <td>{{ $transaction->amount }} €</td>
                                    <td>{{ $transaction->type }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // بيانات الرسم البياني (دخل ومصروف)
    var ctxArea = document.getElementById('myAreaChart').getContext('2d');
    var myAreaChart = new Chart(ctxArea, {
        type: 'line',
        data: {
            labels: @json($chartData->pluck('date')),
            datasets: [
                {
                    label: 'Income',
                    data: @json($chartData->pluck('total_income')),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                },
                {
                    label: 'Expense',
                    data: @json($chartData->pluck('total_expense')),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

 
@endsection
