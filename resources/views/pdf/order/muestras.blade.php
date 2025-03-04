    <p class="muestra-heading">MFQ-{{ $order->folio }} - {{ $muestra->numero_muestra }}</p>
    <div class="container">
        <div class="lg">Tipo de muestra: </div>
        <div>{{ $muestra->tipo_muestra }}</div>
    </div>
    @if(isset($muestra->identificacion_muestra))
        <div class="container">
            <div class="lg">Identificación de la muestra:</div>
            <div>{{ $muestra->identificacion_muestra->identificacion_muestra }}</div>
        </div>
    @endif
     @if($order->aguas_alimentos === 'Aguas' && isset($muestra->identificacion_muestra)) 
        <div class="container">
            <div class="lg">Latitud:</div>
            <div class="medium">{{ $muestra->identificacion_muestra->latitud }}</div>
             <div class="medium">Longitud:</div>
            <div class="block">{{ $muestra->identificacion_muestra->longitud }}</div>
        </div>
    @endif
    <div class="container">
        <div class="lg">Caracteristicas de la muestra: </div>
        <div>{{ $muestra->caracteristicas }}</div>
    </div>
    <div class="container">
        <div class="lg">Muestreador: </div>
        <div class="medium">{{ $muestra->muestreador }}</div>
        @if($order->aguas_alimentos === 'Aguas') 
            <div class="medium">*pH: </div>
            <div class="block small">{{ $muestra->pH }}</div>
            <div class="block small">pH Cr VI: </div>
            <div class="block xs">{{ $muestra->ph_cromo_hexavalente }} </div>
        @endif
    </div>
    @if($order->aguas_alimentos === 'Aguas') 
        <div class="container">
            <div class="lg">Tratada biologicamente: </div>
            <div class="medium"> {{ $muestra->tratada_biologicamente ? 'Si' : 'No' }}</div>
            <div class="medium">Cloro: </div>
            <div class="block small"> {{ $muestra->cloro }}</div>
            @if($muestra->cloro === 'Presente' && $muestra->tipo_muestreo === 'Simple') 
                <div class="block">Valor del cloro: </div>
                <div class="block xs"> {{ $muestra->valor_cloro }}</div>
            @endif
        </div>
    @endif
    <div class="container">
        <div class="lg">Tipo de muestreo: </div>
        <div>{{ snakeToSpace($muestra->tipo_muestreo) }}</div>
    </div>
    <div class="container">
        <div class="lg">Fecha de muestreo: </div>
        <div class="medium"> {{ $muestra->fecha_muestreo }}</div>
        <div class="medium">Hora de muestreo: </div>
        <div> {{ horaValida($muestra->hora_muestreo) }}</div>
    </div>
    @if($order->aguas_alimentos === 'Aguas') 
        @if($muestra->tipo_muestreo != 'Simple' && $muestra->tipo_muestreo != 'N/A') 
            <div class="container">
                <div class="lg">Fecha de fin de muestreo: </div>
                <div class="medium"> {{ $muestra->fecha_final_muestreo }}</div>
                <div class="medium">Hora de fin de muestreo: </div>
                <div> {{ horaValida($muestra->hora_final_muestreo) }}</div>
            </div>
        @endif
        @if($muestra->tipo_muestreo != 'Simple' && $muestra->tipo_muestreo != 'N/A') 
            <div class="container">
                <div class="lg">Fecha de composición: </div>
                <div class="medium"> {{ $muestra->fecha_composicion }}</div>
                <div class="medium">Hora de composición: </div>
                <div> {{ horaValida($muestra->hora_composicion) }}</div>
            </div>
        @endif
        @if($muestra->tipo_muestreo != 'Simple' && $muestra->tipo_muestreo != 'N/A') 
            <div class="container">
                <div class="lg">Flujo 1: </div>
                <div class="medium"> {{ $muestra->flujo_1 . ' (l/s)' }}</div>
                <div class="medium">Flujo 2: </div>
                <div class="block small"> {{ $muestra->flujo_2 . ' (l/s)' }}</div>
                <div class="block small">Flujo 3: </div>
                <div class="block small"> {{ $muestra->flujo_3 . ' (l/s)' }}</div>
            </div>
            <div class="container">
                <div class="lg">Flujo 4: </div>
                <div class="medium"> {{ $muestra->flujo_4 . ' (l/s)' }}</div>
                @if($muestra->tipo_muestreo === 'Compuesto_6') 
                    <div class="medium">Flujo 5: </div>
                    <div class="block small"> {{ $muestra->flujo_5 . ' (l/s)' }}</div>
                    <div class="block small">Flujo 6: </div>
                    <div class="block small"> {{ $muestra->flujo_6 . ' (l/s)' }}</div>
                @endif
            </div>
        @endif
        <div class="container">
            <div class="lg">Preservación correcta: </div>
            <div> {{ $muestra->preservacion_correcta }}</div>
        </div>
    @else 
        <div class="container">
            <div class="lg">Peso/Vol de la muestra g/l: </div>
            <div> {{ $muestra->peso_muestra }}</div>
        </div>
        <div class="container">
            <div class="lg">Temperatura °C: </div>
            <div> {{ $muestra->temperatura }}</div>
        </div>
    @endif
    <div class="container">
        <div class="lg">Parámetros: </div>
        <div> {{ $muestra->parametros }}</div>
    </div>