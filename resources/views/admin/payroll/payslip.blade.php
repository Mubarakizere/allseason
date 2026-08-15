<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payroll->employee_name }} - {{ $payroll->month }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .payslip-card {
            max-width: 700px;
            margin: 30px auto;
            background: #fff;
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
        }
        .payslip-header {
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        @media print {
            body { background: #fff; }
            .payslip-card { box-shadow: none; border: none; margin: 0; padding: 0; width: 100%; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="no-print text-center my-3">
        <button onclick="window.print()" class="btn btn-primary font-weight-bold px-4">
            <i class="fas fa-print me-2"></i> Print Payslip
        </button>
    </div>

    <div class="payslip-card">
        <div class="payslip-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="font-weight-bold mb-1" style="color: #ef4444;">{{ config('site.name') }}</h3>
                <p class="text-muted mb-0 small">Official Employee Salary Payslip</p>
            </div>
            <div class="text-end">
                <h5 class="font-weight-bold mb-0">PAYSLIP</h5>
                <span class="badge bg-secondary">{{ $payroll->month }}</span>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <span class="text-muted small d-block">Employee Name:</span>
                <h5 class="font-weight-bold mb-1">{{ $payroll->employee_name }}</h5>
                <span class="badge bg-light text-dark border">{{ $payroll->employee_type }}</span>
            </div>
            <div class="col-6 text-end">
                <span class="text-muted small d-block">Payment Date:</span>
                <strong class="d-block mb-1">{{ $payroll->payment_date ? $payroll->payment_date->format('d M, Y') : 'N/A' }}</strong>
                <span class="text-muted small d-block">Payment Method:</span>
                <strong>{{ $payroll->payment_method }}</strong>
            </div>
        </div>

        <table class="table table-bordered mb-4">
            <thead class="table-light">
                <tr>
                    <th>Earnings & Allowances</th>
                    <th class="text-end">Amount ({!! $site_settings->currency_symbol ?? 'RWF ' !!})</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary</td>
                    <td class="text-end">{{ number_format($payroll->base_salary, 2) }}</td>
                </tr>
                <tr>
                    <td>Bonuses & Allowances</td>
                    <td class="text-end text-success">+{{ number_format($payroll->bonuses, 2) }}</td>
                </tr>
                <tr class="table-light">
                    <th>Deductions</th>
                    <th class="text-end">Amount ({!! $site_settings->currency_symbol ?? 'RWF ' !!})</th>
                </tr>
                <tr>
                    <td>Total Deductions</td>
                    <td class="text-end text-danger">-{{ number_format($payroll->deductions, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="table-dark">
                    <th class="fs-5">NET SALARY PAID</th>
                    <th class="text-end fs-5">{!! $site_settings->currency_symbol ?? 'RWF ' !!}{{ number_format($payroll->net_salary, 2) }}</th>
                </tr>
            </tfoot>
        </table>

        @if($payroll->notes)
            <div class="mb-4 p-3 bg-light rounded">
                <small class="text-muted d-block font-weight-bold">Notes:</small>
                <span>{{ $payroll->notes }}</span>
            </div>
        @endif

        <div class="row pt-4 border-top mt-5">
            <div class="col-6 text-center">
                <div style="border-top: 1px dashed #666; width: 80%; margin: 40px auto 5px auto;"></div>
                <small class="text-muted">Employer Signature</small>
            </div>
            <div class="col-6 text-center">
                <div style="border-top: 1px dashed #666; width: 80%; margin: 40px auto 5px auto;"></div>
                <small class="text-muted">Employee Signature</small>
            </div>
        </div>
    </div>
</div>

</body>
</html>
