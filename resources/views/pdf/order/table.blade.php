@if($order->observaciones)
    <div class="container">
        <div class="lg">
             <strong>Observaciones: </strong>
        </div>
        <div style="font-size: 10px;">
            <?= $order->observaciones; ?>
        </div>
    </div>
@endif
@if($order->aguas_alimentos === 'Aguas')
    <div class="mt-2">
        <h3 style="font-size:12px;">Verificación de la preservación de la muestra con tira indicadora de pH</h3>
        <p class="verificacion-texto-parrafo">
            *FQ: son las muestras que no requieren preservación (Acidez, Alcalinidad, Cloruros, Color, pH, Conductividad, DBO5, Fluoruros, Fosforo, Nitratos, Nitritos, Solidos y sales disueltas, Solidos sedimentables, Sulfatos, Turbiedad, Huevos de helminto, Olor, {{ $order->fecha_recepcion < '2022-10-21' ? 'Sabor y Cloro).' : 'Sabor, Cloro, Yodo residual libre, Radioactividad alfa y beta global, Plaguicidas, BTEX y Trihalometanos'}}
        </p>
    </div>
@endif
<div>
 <table class="preservation-table">
        <thead>
            <tr>
                <th>Mb</th>
                <th>GyA</th>
                <th>D.Q.O.</th>
                <th>NTK</th>
                <th>CN</th>
                <th>Hg</th>
                <th>Metales</th>
                <th>N-NH3</th>
                <th>Dureza</th>
                <th>SAAM</th>
                <th>Cr VI</th>
                @if($order->fecha_recepcion > '2022-10-20')
                    <th>Fenoles</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>N/A</td>
                <td>pH < 2</td>
                <td>pH < 2</td>
                <td>pH < 2</td>
                <td>pH > 12</td>
                <td>pH < 2</td>
                <td>pH < 2</td>
                <td>pH < 2</td>
                <td>pH < 2</td>
                <td>pH < 2</td>
                <td>pH 9</td>
                @if ($order->fecha_recepcion > '2022-10-20')
                    <td>pH < 2</td>
                @endif
        </tbody>
    </table>
    @if ((int)$order->area_recepcion_muestras_limpia )
        <p class="verificacion-texto-parrafo">
            Se realizó desinfección en el areá de recepción después de recibir la última muestra: Si
        </p>
    @endif
</div>