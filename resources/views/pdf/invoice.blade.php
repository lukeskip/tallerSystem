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

        <h1>Cotización {{$invoice['id']}}</h1>
        <h2>{{$invoice['project']}}</h2>

        @if($invoiceItems->count() > 0)
        <table style="font-size:12px;border-collapse: collapse;">
            <thead style="background-color:#9e915f; color:white;text-transform: uppercase;font-size:14px;">
                <tr>
                    @foreach($invoiceItems->first() as $key => $value)
                        @if($key !== 'id')
                            <th style="border:0;padding:5px">
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
                            <h3>{{ $currentCategory }}</strong>
                        </td>
                    </tr>
                @endif
                <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f3f4f6' }}; border-bottom: 1px solid #d1d5db;">
                    @foreach($item as $key => $value)
                        @if($key !== 'id')
                            <td style="padding:10px 5px">
                                {{ $value }}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            <tr>
                <td>
                    <h2>Total: {{$invoice['amount']}}</h2>
                </td>
            </tr>
            </tbody>
        </table>
        @else
            <p>No hay información que mostrar</p>
        @endif

    </body>
</html>
