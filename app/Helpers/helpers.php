<?php
    use App\Http\Requests\WaterSampleUpdateRequest;
    use App\Http\Requests\WaterSampleUpdateAllRequest;
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
        $str .= $grados . '°' . $minutos . "'" . $segundos . '"' . ' ' . $orientacion;
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