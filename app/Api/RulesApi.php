<?php
    namespace App\Api;
    use App\Models\Rule;
    use Illuminate\Http\Request;
    use App\Models\ParameterCombination;
    use Illuminate\Support\Facades\DB;

    class RulesApi
    {
        public static function show (Request $request, Rule $rule)
        {
            $data = [];
            if ($request->has('page')) {
                $data['page'] = $request->query('page');
            }
            $parametersCombinations = ParameterCombination::where('obsoleto', 0)->get()->map(function ($item) {
                return [
                    'label' => $item->alias,
                    'value' => $item->alias,
                    'key' => $item->id
                ];
            });
            $mainTable = 'normas_combinaciones_parametros_aguas';
            $secTable = 'combinaciones_parametros';
            /*
            $sql = "select parametros.parametro, unidades.nombre, metodos.nombre, " .  $secTable . ".alias" . " from $mainTable join $secTable on {$secTable}.id = {$mainTable}.id_combinacion_parametro join parametros on parametros.id = {$secTable}.id_parametro join unidades on unidades.id = {$secTable}.id_unidad join metodos on metodos.id_metodo = {$secTable}.id_metodo where {$mainTable}.id_norma = :id_norma";
            $rule->parametersCombinations = DB::Raw()
                ->where("{$mainTable}.id_norma", $rule->id)
                ->when(
                    $request->has('paramCombination'),
                    fn ($query, $filter) => $query->where('parametro', 'like', '%' . urldecode($filter) . '%') 
                )
                ->get();
                $data['rule'] = $rule;
                $data['parametersCombinations'] = $parametersCombinations;
                return $data;*/
                $filter = $request->query('paramCombination');
                $rule->parametersCombinations = DB::table($mainTable)
                    ->join($secTable, "{$mainTable}.id_combinacion_parametro", '=', "{$secTable}.id")
                    ->join('parametros', "{$secTable}.id_parametro", '=', 'parametros.id')
                    ->join('unidades', "{$secTable}.id_unidad", '=', 'unidades.id')
                    ->join('metodos', "{$secTable}.id_metodo", '=', 'metodos.id_metodo')
                    ->select('parametros.parametro', 'normas_combinaciones_parametros_aguas.id AS normas_combinaciones_parametros_aguas_id', "{$secTable}.alias", DB::raw('unidades.nombre as nombre_unidad, metodos.nombre as nombre_metodo'))
                    ->where("{$mainTable}.id_norma", $rule->id)
                    ->when(
                        $filter ?? false,
                        function ($query, string $filter) {
                            $query->where('parametros.parametro', 'like', '%' . urldecode($filter) . '%'); 
                        } 
                     )
                    ->get();
            $data['parametersCombinations'] = $parametersCombinations;
            $data['rule'] = $rule;
            if ($request->has('paramCombination')) {
                $data['paramCombination'] = $request->query('paramCombination');
            }
            return $data;
        }


    }