<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            @font-face {
                font-family: roboto;
                font-style: normal;
                font-weight: normal;
                src:  'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap';
            }
    
            body{
                font-family:roboto,sans-serif;
            }
        </style>

    
    </head>
    <body class="font-sans antialiased">

        <div style="display:flex;flex-wrap:wrap;justify-content: space-between; align-items: center;">
            <div>
                <img src="{{asset('./img/logo.svg')}}" style="width:200px" alt="">
            </div>
            <div style="text-align:right;margin-bottom:20px">
                <h2 style="margin:0">Cotización {{$invoice['id']}}</h1>
                <h2 style="margin:0">{{$invoice['project']['name']}}</h2>
                <h3 style="margin:0">{{$invoice['client']}}</h3>
            </div>
        </div>
        

        @if($invoiceItems->count() > 0)
        <table style="font-size:12px;border-collapse: collapse; width:100%">
            <thead style="background-color:#9e915f; color:white;text-transform: uppercase;">
                <tr>
                    @foreach($invoiceItems->first() as $key => $value)
                        @if($key !== 'id' && $key !== 'category')
                            <th style="border:0;padding:5px;text-align:left;font-size:12px;">
                                {{ $key }}
                            </th>
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @php
                $currentCategory = null;
            @endphp
            @foreach($invoiceItems as $index => $item)
                @if ($currentCategory !== $item['category'])
                    @php
                        $currentCategory = $item['category'];
                    @endphp
                    <tr style="background-color: #9e915f; color:white;border-top:solid white 3px;text-transform: uppercase;">
                        <td colspan="{{ count($invoiceItems->first()) }}" style="text-align:center;font-size:12px;">
                            <h3 style="font-size:12px;">{{ $currentCategory }}</strong>
                        </td>
                    </tr>
                @endif
                <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f3f4f6' }}; border-bottom: 1px solid #d1d5db;">
                    @foreach($item as $key => $value)
                        @if($key !== 'id' && $key !== 'category')
                            <td style="padding:10px 5px; border:solid 1px #d1d5db">
                                {{ $value }}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
           
            </tbody>
        </table>
        @else
            <p>No hay información que mostrar</p>
        @endif

        
        @if($incomes->count() > 0)
        <h2>Pagos</h2>
        <table style="font-size:12px;border-collapse: collapse; width:100%">
            <thead style="background-color:#9e915f; color:white;text-transform: uppercase;font-size:14px;">
                <tr>
                    @foreach($incomes->first() as $key => $value)
                        @if($key !== 'id')
                            <th style="border:0;padding:5px">
                                {{ $key }}
                            </th>
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @foreach($incomes as $index => $item)
                <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f3f4f6' }}; border-bottom: 1px solid #d1d5db;">
                    @foreach($item as $key => $value)
                        @if($key !== 'id')
                            <td style="padding:10px 5px; border:solid 1px #d1d5db">
                                {{ $value }}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
           
            </tbody>
        </table>
        @endif

        
        <h3 style="text-align:right;margin-bottom:0">SUBTOTAL: {{$invoice['amount']}}</h3>
        <h3 style="text-align:right;margin:0">IVA: {{$invoice['iva']}}</h3>
        <h3 style="text-align:right;margin:0">PAGADO: {{$invoice['paid']}}</h3>
        <h3 style="text-align:right;margin:0">BALANCE: {{$invoice['balance']}}</h3>


    </body>
</html>
