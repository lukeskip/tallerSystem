<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            
            body{
                font-family: 'Figtree', sans-serif;
            }
            
            .totals{
                margin:80px 0;
                margin-left:80%;  
            }

            .totals td{
                padding: 5px 3px;
                width:20%;
                text-align:right;
                font-size:1em;
                font-weight:bold;
                letter-spacing: 3px;
            }
            .totals td:first-child{
          
                text-align:right;
                padding-right:20px
            }
           
        </style>

    
    </head>
    <body class="font-sans antialiased">

        <div style="display:flex;flex-wrap:wrap;justify-content: space-between; align-items: center;">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 376 82" width="200" >
                    <path style="fill:#af984e" class="cls-1" d="M32.4,23.5l-6.9,40.1h-2.5l6.9-40.1H11.4l.6-2.4h39.4l-.6,2.4h-18.4Z"/>
                    <path style="fill:#af984e" class="cls-1" d="M73,43.5l-.8,4.9c-1.1,5.9-1.3,9.2-1.5,15.2h-2.4c0-2.2.2-4.1.3-5.9-2.7,4.1-7.3,6.2-13.4,6.2s-10.7-3.1-9.6-8.7c.9-5.3,5-8,13.7-8.9l11.1-1.1.2-1.6c1-5.3-2.7-8.5-9.4-8.5s-7.1,1-9.8,2.7l-.5-2c3.1-1.9,6.7-3,10.9-3,8.2,0,12.6,4.1,11.4,10.6ZM58.8,48.5c-7,.7-10.2,2.7-10.9,6.7-.7,4.2,2.1,6.6,8.1,6.6s12.1-3.6,13.2-10.3l.7-4-11,1.1Z"/>
                    <path style="fill:#af984e" class="cls-1" d="M87.8,21.5l2.6-.7-5.9,36.6c-.4,2.4,1,4,3.3,4h2.4l-.6,2.3h-2.5c-3.8,0-5.8-2.5-5.2-6.1l5.8-36.1Z"/>
                    <path style="fill:#af984e" class="cls-1" d="M105.2,21.5l2.6-.7-5.9,36.6c-.4,2.4,1,4,3.3,4h2.4l-.6,2.3h-2.5c-3.8,0-5.8-2.5-5.2-6.1l5.8-36.1Z"/>
                    <path style="fill:#af984e" class="cls-1" d="M145.8,47.4c0,.5-.2,1-.3,1.6h-27.8c-.8,7.5,4.4,12.7,13.1,12.7s7.5-1,10.4-2.7l.5,1.9c-3.3,1.9-7.3,3-11.5,3-10.3,0-16.5-6.7-14.9-16.1,1.5-8.6,8.9-15,17.7-15s14.3,6,12.7,14.5ZM143.6,46.8c.7-6.9-3.8-11.7-10.9-11.7s-13,4.9-14.7,11.7h25.6Z"/>
                    <path style="fill:#af984e" class="cls-1" d="M152.5,63.6l2.7-15.2c1.1-5.9,1.3-9.2,1.5-15.2h2.4c0,3.4-.2,5.9-.5,8.6,3-5.3,8.7-9,14.7-9s2.4.2,3.5.4l-1.3,2.5c-.8-.4-1.8-.5-3-.5-7.1,0-13.6,5.7-14.9,13.1l-2.7,15.2h-2.5Z"/>
                    <g>
                        <path style="fill:#af984e" class="cls-1" d="M260.5,59.2h9.7v-31.4l-5.8,5-2.6-4.5,9.8-7.7h5.3v38.6h9.4v5.3h-25.7v-5.3Z"/>
                        <path style="fill:#af984e" class="cls-1" d="M210,59.2h9.7v-31.4l-5.8,5-2.6-4.5,9.8-7.7h5.3v38.6h9.4v5.3h-25.7v-5.3Z"/>
                        <path style="fill:#af984e" class="cls-1" d="M320.9,25.4c2.4,3.6,3.5,9.1,3.5,16.6s-1.3,13.3-4,17.3c-2.7,4-6.8,5.9-12.3,5.9s-9.6-1.9-12.2-5.7c-2.6-3.8-3.9-9.3-3.9-16.6,0-15.3,5.5-22.9,16.6-22.9,5.7,0,9.8,1.8,12.2,5.4ZM301.2,29.5c-1.4,2.8-2.2,7.1-2.2,13.1s.8,10.1,2.3,12.9c1.5,2.9,3.9,4.3,7.1,4.3s5.6-1.4,7-4.3c1.4-2.9,2.1-7.3,2.1-13.2s-.7-10.2-2-12.9c-1.4-2.7-3.7-4.1-7.1-4.1s-5.8,1.4-7.3,4.2Z"/>
                        <path style="fill:#af984e" class="cls-1" d="M361.4,25.4c2.4,3.6,3.5,9.1,3.5,16.6s-1.3,13.3-4,17.3c-2.7,4-6.8,5.9-12.3,5.9s-9.6-1.9-12.2-5.7c-2.6-3.8-3.9-9.3-3.9-16.6,0-15.3,5.5-22.9,16.6-22.9,5.7,0,9.8,1.8,12.2,5.4ZM341.7,29.5c-1.4,2.8-2.2,7.1-2.2,13.1s.8,10.1,2.3,12.9c1.5,2.9,3.9,4.3,7.1,4.3s5.6-1.4,7-4.3c1.4-2.9,2.1-7.3,2.1-13.2s-.7-10.2-2-12.9c-1.4-2.7-3.7-4.1-7.1-4.1s-5.8,1.4-7.3,4.2Z"/>
                        <rect style="fill:#af984e" class="cls-1" x="243" y="36.4" width="7.8" height="7.8"/>
                    </g>
                    <rect style="fill:#af984e" class="cls-1" x="243.9" y="54.6" width="8" height="8"/>
                </svg>
            </div>
            <div style="text-align:right;">
                <h2 style="margin:0;font-size:1em;">Cotización {{$invoice['id']}}</h2>
                <h2 style="margin:0;color:black;font-size:1em;">{{$invoice['project']['name']}}</h2>
                <h3 style="margin:0;color:black;font-size:.7em;margin-bottom:20px">{{$invoice['client']}}</h3>
                @if($invoice['executive'])
                    <h3 style="margin:0;color:black;font-size:.7em;margin-bottom:20px">Ejecutivo {{$invoice['executive']}}</h3>
                @endif
            </div>
        </div>
        

        @if($invoiceItems->count() > 0)
        <table style="font-size:12px;border-collapse: collapse; width:100%">
            <thead style="background-color:#af984e; color:white;text-transform: uppercase;">
                <tr style="background-color:#af984e; ">
                    @foreach($invoiceItems->first() as $key => $value)
                        @if($key !== 'id' && $key !== 'category')
                            <th style="border:0;padding:5px;text-align:left;font-size:12px;">
                                <h3 style="color:white">{{ $key }}</h3>
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
                    <tr style="background-color: #af984e; color:white;border-top:solid white 5px;text-transform: uppercase;">
                        <td colspan="{{ count($invoiceItems->first()) }}" style="text-align:center;font-size:12px;border-top:solid white 3px;">
                            <h3 style="font-size:12px;color:white">{{ $currentCategory }}</strong>
                        </td>
                    </tr>
                @endif
                <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f3f4f6' }}; border-bottom: 1px solid #d1d5db;">
                    @foreach($item as $key => $value)
                        @if($key !== 'id' && $key !== 'category')
                            <td style="padding:5px 5px; border:solid 1px #d1d5db">
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
            <thead style="background-color:#af984e; color:white;text-transform: uppercase;font-size:14px;">
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

        <div>
            <table style="font-size:12px;border-collapse: collapse; width:100%" class="totals">
                <tr style="background:#f3f4f6;">
                    <td style="width:80%">
                        Subtotal:
                    </td>
                    <td style="">
                        {{$invoice['subtotal']}}
                    </td>
                </tr>
                <tr style="background:white">
                    <td style=" width:80%">
                        Fee ({{$invoice['fee']}}):
                    </td>
                    <td style="">
                        {{$invoice['fee_amount']}}
                    </td>
                </tr>
                <tr style="background:#f3f4f6">
                    <td style=" width:80%">
                        Subtotal:
                    </td>
                    <td style="">
                        {{$invoice['subtotal_fee']}}
                    </td>
                </tr>
                @if($invoice['hasIva'])
                <tr>
                    <td style=" width:80%">
                        IVA ({{$invoice['iva']}}):
                    </td>
                    <td style="">
                        {{$invoice['iva_amount']}}
                    </td>
                </tr>
                @endif
                <tr style="background-color:#af984e;">
                    <td style="width:80%;color:white">
                        Total:
                    </td>
                    <td style="color:white">
                        {{$invoice['total']}}
                    </td>
                </tr>
                <tr>
                    <td style=" width:80%">
                        Pagado:
                    </td>
                    <td style="">
                        {{$invoice['amount_paid']}}
                    </td>
                </tr>
                <tr style="background:#f3f4f6">
                    <td style="width:80%">
                        Balance:
                    </td>
                    <td style="">
                        {{$invoice['balance']}}
                    </td>
                </tr>
            </table>
        </div>
       <table style="font-size:12px;border-collapse: collapse; width:100%">
            <tbody>
                <tr>
                    <td>* Cotización válida por 8 días</td>
                </tr>
                <tr>
                    <td>* Se requiere anticipo de 70% del total del pedido</td>
                </tr>
                <tr>
                    <td>* Notificar al pagar el anticipo si va a requerir factura</td>
                </tr>
                <tr>
                    <td>* Cualquier cambio ajeno a esta cotización realizado por el cliente, el pago deberá ser cubierto por él al momento, para su ejecución</td>
                </tr>
                <tr>
                    <td>* Tiempos de entrega sujetos a confirmación de proveedores</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
