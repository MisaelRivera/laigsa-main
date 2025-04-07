<?php
    use App\Http\Requests\WaterSampleUpdateRequest;
    use App\Http\Requests\WaterSampleUpdateAllRequest;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\URL;

    function getPreviousURL () {
        $previousUrl = URL::previous();
        // Create a request object for the previous URL
        $previousRequest = Request::create($previousUrl);

        // Match the previous request to a route
        $previousRoute = Route::getRoutes()->match($previousRequest);

        // Get the name of the previous route, if it has one
        return $previousRoute->getName();
    }

    function removeDynamicPostfixFromKeys(array $array): array
    {
        $result = [];
    
        foreach ($array as $key => $value) {
            // Use regex to check if the key ends with a numeric postfix like _1, _2, etc.
            $newKey = preg_replace('/_\d+$/', '', $key);
    
            // Add the modified key-value pair to the result array
            $result[$newKey] = $value;
        }
    
        return $result;
    } 

    function handleSingularCasesOnUpdateWaterSample(WaterSampleUpdateRequest $request, $validatedData)
    {
        $filteredData = $validatedData;
        $isSimple = $validatedData['tipo_muestreo'] === 'Simple';
        $isCompuesto4 = $validatedData['tipo_muestreo'] === 'Compuesto_4';
        $isCompuesto6 = $validatedData['tipo_muestreo'] === 'Compuesto_6';
        $isCloroPresente = $validatedData['cloro'] === 'Presente';
        $isCloroAusente = $validatedData['cloro'] === 'Ausente';
        if (!$isCompuesto4 && !$isCompuesto6) {
            $filteredData['fecha_final_muestreo'] = null;
            $filteredData['hora_final_muestreo'] = null;
            $filteredData['fecha_composicion'] = null;
            $filteredData['hora_composicion'] = null;
            $filteredData['flujo_1'] = 'NA';
            $filteredData['flujo_2'] = 'NA';
            $filteredData['flujo_3'] = 'NA';
            $filteredData['flujo_4'] = 'NA';
            $filteredData['flujo_5'] = 'NA';
            $filteredData['flujo_6'] = 'NA';
        }

        if (!$isSimple || (!$isCloroAusente && !$isCloroPresente)) {
            $filteredData['valor_cloro'] = 'N/A';
        }

        if (in_array('otros', array_keys($filteredData))) {
            $otros_parametros = $filteredData['otros'];
            $filteredData = $request->safe()->except('otros');
            $filteredData['otros_parametros'] = 1;
            $filteredData['parametros'] = $otros_parametros;
        }

        return $filteredData;
    }

    function handleSingularCasesOnUpdateAllWaterSamples($validatedData)
    {
        $filteredData = $validatedData;
        $isSimple = $validatedData['tipo_muestreo'] === 'Simple';
        $isCompuesto4 = $validatedData['tipo_muestreo'] === 'Compuesto_4';
        $isCompuesto6 = $validatedData['tipo_muestreo'] === 'Compuesto_6';
        $isCloroPresente = $validatedData['cloro'] === 'Presente';
        $isCloroAusente = $validatedData['cloro'] === 'Ausente';
        if (!$isCompuesto4 && !$isCompuesto6) {
            $filteredData['fecha_final_muestreo'] = null;
            $filteredData['hora_final_muestreo'] = null;
            $filteredData['fecha_composicion'] = null;
            $filteredData['hora_composicion'] = null;
            $filteredData['flujo_1'] = 'NA';
            $filteredData['flujo_2'] = 'NA';
            $filteredData['flujo_3'] = 'NA';
            $filteredData['flujo_4'] = 'NA';
            $filteredData['flujo_5'] = 'NA';
            $filteredData['flujo_6'] = 'NA';
        }

        if (!$isSimple || (!$isCloroAusente && !$isCloroPresente)) {
            $filteredData['valor_cloro'] = 'N/A';
        }

        if (in_array('otros', array_keys($filteredData))) {
            $otros_parametros = $filteredData['otros'];
            unset($filteredData['otros']);
            $filteredData['otros_parametros'] = 1;
            $filteredData['parametros'] = $otros_parametros;
        }

        return $filteredData;
    }

    function implodingCoordinates ($grados, $minutos, $segundos, $orientacion)
    {
        $str = '';
        $str .= $grados . '°' . $minutos . "'" . $segundos . "''" . ' ' . $orientacion;
        return $str;
    }

    function explodingCoordinates ($str)
    {
        $strArr1 = explode('°', $str);
        $grados = $strArr1[0];
        $strArr2 = explode("'", $strArr1[1]);
        $minutos = $strArr2[0];
        $strArr3 = explode('"', $strArr2[1]);
        $segundos = $strArr3[0];
        $orientacion = trim($strArr3[1]);
        return [
            'grados' => $grados,
            'minutos' => $minutos,
            'segundos' => $segundos,
            'orientacion' => $orientacion
        ];
    }

    function unsettingCoordinates ($data) {
        unset($data['latitud_grados']);
        unset($data['latitud_minutos']);
        unset($data['latitud_segundos']);
        unset($data['latitud_orientacion']);
        unset($data['longitud_grados']);
        unset($data['longitud_minutos']);
        unset($data['longitud_segundos']);
        unset($data['longitud_orientacion']);
        return $data;
    }

    function handleSingularCasesOnUpdateAllFoodSamples($validatedData, $request, $number)
    {
        $filteredData = $validatedData;
        if (isset($filteredData['latitud_grados'])) {
            $filteredData['latitud'] = implodingCoordinates(
                $filteredData['latitud_grados'],
                $filteredData['latitud_minutos'],
                $filteredData['latitud_segundos'],
                $filteredData['latitud_orientacion']
            );
            unset($filteredData['latitud_grados']);
            unset($filteredData['latitud_minutos']);
            unset($filteredData['latitud_segundos']);
            unset($filteredData['latitud_orientacion']);
    
            $filteredData['longitud'] = implodingCoordinates(
                $filteredData['longitud_grados'],
                $filteredData['longitud_minutos'],
                $filteredData['longitud_segundos'],
                $filteredData['longitud_orientacion']
            );
            unset($filteredData['longitud_grados']);
            unset($filteredData['longitud_minutos']);
            unset($filteredData['longitud_segundos']);
            unset($filteredData['longitud_orientacion']);
        } else {
            $filteredData['latitud'] = 'N/A';
            $filteredData['longitud'] = 'N/A';
        }

        if ($request->has("peso_muestra_" . $number)) {
            $filteredData['peso_muestra'] = $request->input("peso_muestra_" . $number);
        } else {
            $filteredData['peso_muestra'] = 'N/A';
        }

        if ($request->has("temperatura_" . $number)) {
            $filteredData['temperatura'] = $request->input("temperatura_" . $number);
        } else {
            $filteredData['temperatura'] = null;
        }
        
        if (in_array('otros', array_keys($filteredData))) {
            $otros_parametros = $filteredData['otros'];
            unset($filteredData['otros']);
            $filteredData['otros_parametros'] = 1;
            $filteredData['parametros'] = $otros_parametros;
        }

        return $filteredData;
    }

    function is_sunday ($date_in_time) {
    return getdate($date_in_time)['wday'] == 0;
}

function is_laboral_date ($date_in_time) {
    $day = getdate($date_in_time)['mday'];
    $month = getdate($date_in_time)['mon'];
    $holidays_dates = [[1, 1], [7, 2], [21, 3], [16, 9], [25, 12]];
    return !in_array([$day, $month], $holidays_dates);
}

function get_future_day ($initial_date, $days) {
    if (isset($initial_date)) {
        $date_in_time = strtotime($initial_date);
        $counter = 0;
        while ($counter < $days) {
            $date_in_time += (60 * 60 * 24);
            if (!is_sunday($date_in_time) && is_laboral_date($date_in_time)) {
                $counter++;
            }
        }
        return date('Y-m-d', $date_in_time);
    } else {
        return '---';
    }
}

function get_diff ($date_1, $date_2) {
    if (!isset($date_1) || !isset($date_2)) return null;

    if (gettype($date_1) === 'string' && $date_1 != 'today') {
        $date_1 = strtotime($date_1);
    } else if ($date_1 === 'today') {
        $today = date('Y-m-d', time());
        $date_1 = strtotime($today);
    }

    if (gettype($date_2) === 'string' && $date_2 != 'today') {
        $date_2 = strtotime($date_2);
    } else if ($date_2 === 'today') {
        $today = date('Y-m-d', time());
        $date_2 = strtotime($today);
    }

    return $date_1 - $date_2;
}

function calc_days ($time) {
    if (!isset($time)) return null; 
    return floor($time / (60 * 60 * 24));
}

function get_colors_by_date($remaining_days) {
    if ($remaining_days <= 2 && $remaining_days > 0) return '#fff;background-color:#ec971f;';
    
    if ($remaining_days == 0) return '#fff;background-color:#d9534f;';

    return 'black';

}

function horaValida($hora)
{
    if ($hora !== '-----' && $hora && $hora !== '---') {
        $hora = explode(':', $hora);
        return $hora[0] . ':' . $hora[1];
    } else if ($hora == '---') {
        return '---';
    }
    return '-----';
}

function snakeToSpace ($snakeCase) {
    $snakeCase = explode('_', $snakeCase);
    return implode(' ', $snakeCase);
}