<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    @php
        $settingValue = getSettingValue();
        \Carbon\Carbon::setlocale(config('app.locale'));
    @endphp
    <link rel="icon" href="{{ $settingValue['favicon']['value'] }}" type="image/png">
    <title>Company Invoice Details</title>

    <style type="text/css">
        * {
            font-size: 14px;
            line-height: 24px;
            font-family: 'Ubuntu', sans-serif;
            text-transform: capitalize;
        }

        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor: pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }

        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }

        tr {
            border-bottom: 1px dotted #ddd;
        }

        td,
        th {
            padding: 7px 0;
            width: 50%;
        }

        table {
            width: 100%;
        }

        tfoot tr th:first-child {
            text-align: left;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        small {
            font-size: 11px;
        }

        @media print {
            * {
                font-size: 12px;
                line-height: 20px;
            }

            td,
            th {
                padding: 5px 0;
            }

            .hidden-print {
                display: none !important;
            }

            @page {
                margin: 1.5cm 0.5cm 0.5cm;
            }

            @page: first {
                margin-top: 0.5cm;
            }
        }
    </style>
</head>

<body>

    <div style="width:100%; max-width:400px;margin:0 auto">
       
        <div id="receipt-data">
            <div class="centered">
                @if($settingValue['app_logo']['value'])
                <img src="{{url($settingValue['app_logo']['value'])}}" height="42" width="50" style="margin:10px 0;">
                @endif

                <h2>{{ $settingValue['app_name']['value'] }}</h2>

                <p>Address: {{ $settingValue['hospital_address']['value'] ?? 'N/A' }}
                </p>
            </div>
            <p>Invoice Date: {{ $invoice->invoice_date->format('jS M, Y') }}<br>
                Invoice No.: {{ $invoice->invoice_id ?? '' }}<br>
                Patient: {{ $invoice->patient->user->full_name }}<br>
                @if($invoice->patient->company)
                Company: {{ $invoice->patient->company->name }}<br>
                @endif
            </p>
            <table class="table-data">
                <tbody>
                    @foreach ($invoice->invoiceItems as $key => $invoiceItems)
                    
                    <tr>
                        <td colspan="2">
                            @if($invoiceItems->charge && $invoiceItems->charge->chargeCategory)
                                {{ $invoiceItems->charge->chargeCategory->name }}
                            @elseif($invoiceItems->charge)
                                {{ $invoiceItems->charge->code }}
                            @else
                                {{ $invoiceItems->description }}
                            @endif
                            <br>{{ $invoiceItems->quantity }} x {{ checkNumberFormat($invoiceItems->price, strtoupper(getCurrentCurrency())) }}
                        </td>
                        <td style="text-align:right;vertical-align:bottom">
                            {{ checkNumberFormat($invoiceItems->total, strtoupper(getCurrentCurrency())) }}
                        </td>
                    </tr>
                    @endforeach

                    <tr>
                        <th colspan="2" style="text-align:left">Sub Total:</th>
                        <th style="text-align:right">{{ checkNumberFormat($invoice->amount, strtoupper(getCurrentCurrency())) }}</th>
                    </tr>
                 
                    @if($invoice->discount > 0)
                    <tr>
                        <th colspan="2" style="text-align:left">Discount:</th>
                        <th style="text-align:right">{{ checkNumberFormat(($invoice->amount * $invoice->discount) / 100, strtoupper(getCurrentCurrency())) }}
                                    ({{ $invoice->discount }}<span style="font-family: DejaVu Sans">&#37;</span>)</th>
                    </tr>
                    @endif
                    
                    <tr>
                        <th colspan="2" style="text-align:left">Total:</th>
                        <th style="text-align:right">{{ checkNumberFormat($invoice->amount - (($invoice->amount * ($invoice->discount ?? 0)) / 100), strtoupper(getCurrentCurrency())) }}</th>
                    </tr>
                    
                    @if($invoice->balance > 0)
                    <tr>
                        <th colspan="2" style="text-align:left">Balance:</th>
                        <th style="text-align:right">{{ checkNumberFormat($invoice->balance, strtoupper(getCurrentCurrency())) }}</th>
                    </tr>
                    @endif
                </tbody>
            </table>
            <table>
                <tbody>
                    <tr>
                        <td class="centered" colspan="3">Thank you for your business</td>
                    </tr>
                </tbody>
            </table>
           
        </div>
    </div>

    <script>
        window.onload = (event) => {
            setTimeout(function() {
                window.print();
            }, 500);
            window.onfocus = function() {
                setTimeout(function() {
                    window.close();
                }, 500);
            }
        };
        window.onafterprint = function() {
            window.close()
        };
    </script>

</body>

</html>