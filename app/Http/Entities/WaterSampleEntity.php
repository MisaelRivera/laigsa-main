<?php
    namespace App\Http\Entities;
    class WaterSampleEntity
    {
        private $numero_muestra;
        private $tipo_muestra;
        private $caracteristicas;
        private $muestreador;
        private $fecha_muestreo;
        private $fecha_final_muestra = null;
        private $hora_muestreo;
        private $hora_final_muestreo = null;
        
    }