<?php
    use App\Http\Requests\WaterSampleUpdateRequest;
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