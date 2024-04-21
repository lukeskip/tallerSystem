<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

    
        <script>
            window.csrf_token = "{{ csrf_token() }}";
            window.app_url = "{{ url('/') }}"
        </script>
    </head>
    <body class="font-sans antialiased">

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
                @foreach($invoiceItems as $item)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        @foreach($item as $key => $value)
                            @if($key !== 'id')
                                <td class="border px-4 py-2">
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

    </body>
</html>
