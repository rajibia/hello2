<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    @php
        $settingValue = getSettingValue();
        \Carbon\Carbon::setlocale(config('app.locale'));
    @endphp
    <link rel="icon" href="{{ $settingValue['favicon']['value'] }}" type="image/png">
    <title>{{ __('messages.invoice.invoice_details') }}</title>

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

            /*tbody::after {
                content: ''; display: block;
                page-break-after: always;
                page-break-inside: avoid;
                page-break-before: avoid;
            }*/
        }
    </style>
</head>

<body>

    <div style="width:100%; max-width:400px;margin:0 auto">
       
        <div id="receipt-data">
            <div class="centered">
                @if($setting['app_logo'])
                <img src="{{url($setting['app_logo'])}}" height="42" width="50" style="margin:10px 0;">
                @endif

                <h2>{{ $setting['app_name'] }}</h2>

                <p>{{ __('messages.common.address') }}: {{ $setting['hospital_address'] }}
                </p>
            </div>
            <p>{{ __('messages.invoice.invoice_date') }}: {{ $invoice->invoice_date->format('jS M, Y') }}<br>
                Invoice No.: {{ $invoice->invoice_id ?? '' }}<br>
            {{ __('messages.advanced_payment.patient') }}: {{ $invoice->patient->user->full_name }}
               
            </p>
            <table class="table-data">
                <tbody>
                    @foreach ($invoice->invoiceItems as $key => $invoiceItems)
                    
                    <tr>
                        <td colspan="2">
                            {{ $invoiceItems->charge->chargeCategory->name }}
                            <br>{{ $invoiceItems->quantity }} x  {{ checkValidCurrency($invoice->currency_symbol ?? getCurrentCurrency())
                                                ? moneyFormat(strtoupper($invoice->currency_symbol ?? getCurrentCurrency()), $invoiceItems->price)
                                                : getCurrencySymbol() . number_format($invoiceItems->price) }}
                          
                        </td>
                        <td style="text-align:right;vertical-align:bottom">
                        {{ checkValidCurrency($invoice->currency_symbol ?? getCurrentCurrency()) ? moneyFormat(strtoupper($invoice->currency_symbol ?? getCurrentCurrency()), $invoiceItems->total) : getCurrencySymbol() . number_format($invoiceItems->total) }}
                        </td>
                    </tr>
                    @endforeach

                    <!-- <tfoot> -->
                    <tr>
                        <th colspan="2" style="text-align:left">{{ __('messages.invoice.sub_total') }}:</th>
                        <th style="text-align:right">{{ checkValidCurrency($invoice->currency_symbol ?? getCurrentCurrency())
                                        ? moneyFormat(strtoupper($invoice->currency_symbol ?? getCurrentCurrency()), $invoice->amount)
                                        : getCurrencySymbol() . number_format($invoice->amount) }}</th>
                    </tr>
                 
                    <tr>
                        <th colspan="2" style="text-align:left">{{ __('messages.invoice.discount') }}:</th>
                        <th style="text-align:right"> {{ checkValidCurrency($invoice->currency_symbol ?? getCurrentCurrency())
                                        ? moneyFormat(
                                            strtoupper($invoice->currency_symbol ?? getCurrentCurrency()),
                                            ($invoice->amount * $invoice->discount) / 100,
                                        )
                                        : getCurrencySymbol() . number_format(($invoice->amount * $invoice->discount) / 100, 2) }}
                                    ({{ $invoice->discount }}<span style="font-family: DejaVu Sans">&#37;</span>)</th>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align:left">{{ __('messages.invoice.total') }}:</th>
                        <th style="text-align:right"> {{ checkValidCurrency($invoice->currency_symbol ?? getCurrentCurrency())
                                        ? moneyFormat(
                                            strtoupper($invoice->currency_symbol ?? getCurrentCurrency()),
                                            $invoice->amount - ($invoice->amount * $invoice->discount) / 100,
                                        )
                                        : getCurrencySymbol() . number_format($invoice->amount - ($invoice->amount * $invoice->discount) / 100, 2) }}</th>
                    </tr>
                </tbody>
                <!-- </tfoot> -->
            </table>
            <table>
                <tbody>
                    <tr>
                        <td class="centered" colspan="3">Thank you for shopping with us. Please come
                            again</td>
                    </tr>
                    <tr style="border: none;">
                        <td colspan="3" style="display: flex;align-items: center; justify-content: center;width: 100%;">
                            <?php 
                              $url = '127.0.0.1:8000';
                            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(90)->generate($url);  
                            
                            ?>
                            {{$qrCode}}
                               
                        </td>
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