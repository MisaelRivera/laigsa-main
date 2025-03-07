@if($order->observaciones)
    <div class="container">
        <div class="lg">
             <strong>Observaciones: </strong>
        </div>
        <div style="font-size: 10px;">
            {{ $order->observaciones }}
        </div>
    </div>
@endif
@if($order->aguas_alimentos === 'Aguas')
    <h3 class="verificacion-texto-h3" style="font-size:12px;">Verificación de la preservación de la muestra con tira indicadora de pH</h3>
    <p class="verificacion-texto-parrafo">
        *FQ: son las muestras que no requieren preservación (Acidez, Alcalinidad, Cloruros, Color, pH, Conductividad, DBO5, Fluoruros, Fosforo, Nitratos, Nitritos, Solidos y sales disueltas, Solidos sedimentables, Sulfatos, Turbiedad, Huevos de helminto, Olor, {{ $order->fecha_recepcion < '2022-10-21' ? 'Sabor y Cloro).' : 'Sabor, Cloro, Yodo residual libre, Radioactividad alfa y beta global, Plaguicidas, BTEX y Trihalometanos'}}
    </p>
    @if((int)$order->area_recepcion_muestras_limpia )
        <p class="verificacion-texto-parrafo">
            Se realizó desinfección en el areá de recepción después de recibir la última muestra: Si
        </p>
    @endif
@endif