<?php
    namespace App\Http\Entities;
    use App\Models\WaterSamplesResults;
    use App\Models\WaterSample;
    use App\Models\ParameterCombination;
    use Illuminate\Http\Request;

    class WaterSampleResultEntity
    {

        public static function parseParameterToResult ($parameterCombinationId, Request $request, $index, $id_muestra)
        {
            $parameterCombination = ParameterCombination::with(['parametro', 'unidad', 'metodo', 'lcp', 'analistas', 'supervisores'])
                ->where('id', $parameterCombinationId)
                ->first();
            $analistasIds = $parameterCombination->analistas
                ->map(function ($analista) {
                    return $analista->id;
                })->toArray();
            $supervisoresIds = $parameterCombination->supervisores
                ->map(function ($analista) {
                    return $analista->id;
                })->toArray();
            $muestra = WaterSample::with('norma')->where('id', $id_muestra)->first();
            $resultado = [
                'parametro' => $parameterCombination->parametro->parametro,
                'metodo' =>  $parameterCombination->metodo->nombre,
                'lcp' => $parameterCombination->lcp->valor,
                'unidades' => $parameterCombination->unidad->nombre,
                'alias' => $parameterCombination->alias,
                'abreviacion' => $parameterCombination->abreviacion,
                'es_compuesto' => $parameterCombination->compuesto,
                'ema' => $parameterCombination->ema,
                'cna' => $parameterCombination->cna,
                'ssa' => $parameterCombination->ssa,
                'tiene_fecha_resultado_final' => $parameterCombination->fecha_resultado_final,
                'es_parametro_campo' => $parameterCombination->parametro_campo,
                'tiene_incertidumbre' => $request->input("tiene_incertidumbre_" . $index) ? 1:0,
                'decimales' => $parameterCombination->decimales,
                'es_microbiologia' => $parameterCombination->microbiologia,
                'tiene_supervision' => $parameterCombination->supervisar,
                'norma' => $muestra->norma->norma,
                'id_muestra' => $id_muestra,
                'analistas_habilitados' => $analistasIds,
                'supervisores_habilitados' => $supervisoresIds
            ];
            
            if ($resultado['tiene_incertidumbre'] === 1) {
                $resultado['incertidumbre'] = $request->input("incertidumbre_" . $index);
            }

            return WaterSamplesResults::create($resultado);
        }
        
    }