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
        <table class="w-full text-md text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="sticky top-0 text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    @foreach($invoiceItems->first() as $key => $value)
                        @if($key !== 'id')
                            <th class="px-6 py-3">
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
            @foreach($invoiceItems as $item)
                @if ($currentCategory !== $item['category'])
                    @php
                        $currentCategory = $item['category'];
                    @endphp
                    <tr style="background-color: #f3f4f6;">
                        <td colspan="{{ count($invoiceItems->first()) }}" style="text-align:center">
                            <h3>{{ $currentCategory }}</strong>
                        </td>
                    </tr>
                @endif
                <tr style="background-color: #ffffff; border-bottom: 1px solid #d1d5db;">
                    @foreach($item as $key => $value)
                        @if($key !== 'id')
                            <td class="border px-4 py-2">
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
