<?php
    namespace App\Http\Entities;
    use App\Models\WaterSamplesResults;
    use App\Models\ParameterCombination;
    use Illuminate\Http\Request;

    class WaterSampleResultEntity
    {

        public function parseParameterToResult ($parameterCombinationId, Request $request, $index, $id_muestra)
        {
            $parameterCombination = ParameterCombination::with(['parametro', 'unidad', 'metodo', 'lcp'])
                ->where('id', $parameterCombinationId)
                ->first();
            $resultado = [
                'parametro' => $parameterCombination->parametro->parametro,
                'metodo' =>  $parameterCombination->metodo->nombre,
                'lcp' => $parameterCombination->lcp->valor,
                'unidades' => $parameterCombination->unidad->nombre,
                'alias' => $parameterCombination->alias,
                'es_compuesto' => $parameterCombination->compuesto,
                'ema' => $parameterCombination->ema,
                'cna' => $parameterCombination->cna,
                'ssa' => $parameterCombination->ssa,
                'tiene_fecha_resultado_final' => $parameterCombination->fecha_resultado_final,
                'es_parametro_campo' => $parameterCombination->parametro_campo,
                'tiene_incertidumbre' => $parameterCombination->incertidumbre,
                'decimales' => $parameterCombination,
                'es_microbiologia' => $parameterCombination->microbiologia,
                'tiene_supervision' => $parameterCombination->supervisar,
                'norma' => $request->input("norma_" . $index),
                'id_muestra' => $id_muestra,
                'abreviacion' => $parameterCombination->abreviacion
            ];
        }
        
    }