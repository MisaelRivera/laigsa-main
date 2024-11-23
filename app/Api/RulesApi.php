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
            $parametersCombinations = ParameterCombination::all()->map(function ($item) {
                return [
                    'label' => $item->alias,
                    'value' => $item->alias,
                    'key' => $item->id
                ];
            });
            $params = ['id_norma' => $rule->id];
            $mainTable = 'normas_combinaciones_parametros_aguas';
            $secTable = 'combinaciones_parametros';
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
                return $data;
        }
    }