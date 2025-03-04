<br>
    <h3 class="document-title third">
        Libreta de recepción
    </h3>
    <div class="container">
        <div class="block"><b>Dirigir informe a:</b></div>
        <div class="block"></div>
        <div class="block"></div>
        <div class="block"></div>
        <div class="block small"></div>
        <div><b>no. de folio:</b> MFQ-{{ $order->folio }}</div>
    </div>
    <div class="container">
        <div class="lg">Razón social/cliente: </div>
        <div>{{ $cliente->cliente }}</div>
    </div>
    <div class="container">
        <div class="lg">Dirección fiscal: </div>
        <div>{{ $cliente->direccion_fiscal && strlen($cliente->direccion_fiscal) > 0 ? $cliente->direccion_fiscal:'Sin Dato' }}</div>
    </div>
    <div class="container mb">
        <div class="lg">Sitio del muestreo: </div>
        <div class="">{{ $cliente->direccion_muestreo }}</div>
    </div>
    <div class="container">
        <div class="lg">Fecha y hora de entrega: </div>
        <div class="block">{{ $order->fecha_recepcion . ' ' .  $order->hora_recepcion}}</div>
        <div class="block">No. de muestras: </div>
        <div class="block xs">{{ $order->numero_muestras }}</div>
        @if($order->fecha_recepcion < '2022-10-21')
            <div class="lg">Fecha de entrega de resultados: </div>
            <div class="block small"> {{ get_future_day($order->fecha_recepcion, 10) }}</div>
        @endif
    </div>
    <div class="container">
        @if($order->aguas_alimentos === 'Aguas')
            <div class="lg">Temperatura: </div>
            <div class="block">{{ $order->temperatura . '°C' }}</div>
            <div class="block">Termómetro No: </div>
            <div class="block xs">{{ $order->numero_termometro }}</div>
        @else
            <div class="lg">Termómetro No: </div>
            <div class="block">{{ $order->numero_termometro }}</div>
        @endif
        @if($order->fecha_recepcion < '2022-10-21')
            <div class="lg">Cotización No: </div>
            <div class="block small">{{ $order->numero_cotizacion }}</div>
        @endif
    </div>
     @if($order->fecha_recepcion > '2022-10-20')
        <div class="container">
            <div class="big">Fecha de entrega de resultados del analista: </div>
            <div class="block small">{{ get_future_day($order->fecha_recepcion, 8) }}</div>
        </div>
        <div class="container">
            <div class="big">Fecha de entrega de resultados del cliente: </div>
            <div class="block small">{{ get_future_day($order->fecha_recepcion, 10) }}</div>
        </div>
        <div class="container">
            <div class="block2">Cotización No: </div>
            <div class="xl">{{ $order->numero_cotizacion }}</div>
        </div>
    @endif
    <hr>