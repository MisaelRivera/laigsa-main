<script setup>
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({
        errors: Object,
        parameter: Object,
    });
    const form = useForm({
        parametro: props.parameter.parametro,
        abreviacion: props.parameter.abreviacion,
        arrange: props.parameter.arrange,
        subcontratado: props.parameter.subcontratado,
        compuesto: props.parameter.compuesto,
        ema: props.parameter.ema,
        cna: props.parameter.cna,
        ssa: props.parameter.ssa,
        supervisar: props.parameter.supervisar,
        fecha_resultado_final: props.parameter.fecha_resultado_final,
        parametro_campo: props.parameter.parametro_campo,
        incertidumbre: props.parameter.incertidumbre,
    });
    const handleUpdateSubmit = (form$, FormData) => {
        form.put(`/parameters/${props.parameter.id}`);
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-10/12 p-5 rounded-lg bg-gray-100">
           <CreateTitle 
                :title="`Editar el parametro ${parameter.parametro}`"
                :own-link="route('parameters.create')"
                :back-link="route('parameters.index')"/>
            <Vueform
                :endpoint="false"
                :display-errors="false"
                @submit="handleUpdateSubmit"
                v-model="form"
                :scroll-to-invalid="false"
                :columns="{container: 12, wrapper: 12}"
                sync>
                <TextElement 
                    name="parametro"
                    before="Parametro"
                    v-model="form.parametro"
                    :description="errors.parametro ? `<p class='text-red-500'>${errors.parametro}</p>`:null"
                    :columns="{container: 3, wrapper:12}"/>
                <TextElement 
                    name="abreviacion"
                    before="Abreviacion"
                    v-model="form.abreviacion"
                    :description="errors.abreviacion ? `<p class='text-red-500'>${errors.abreviacion}</p>`:null"
                    :columns="{container: 3, wrapper:12}"/>
                <TextElement 
                    name="arrange"
                    before="Orden de acomodo"
                    v-model="form.arrange"
                    input-type="number"
                    :description="errors.arrange ? `<p class='text-red-500'>${errors.arrange}</p>`:null"
                    :columns="{container: 3, wrapper:12}"/>
                <CheckboxElement
                    name="subcontratado"
                    :columns="{ container:3, wrapper:12 }"
                    v-model="form.subcontratado"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    Subcontratado
                </CheckboxElement>
                <CheckboxElement
                    name="compuesto"
                    :columns="{ container:2, wrapper:12 }"
                    v-model="form.compuesto"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    Compuesto
                </CheckboxElement>
                <CheckboxElement
                    name="ema"
                    :columns="{ container:2, wrapper:12 }"
                    v-model="form.ema"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    Ema
                </CheckboxElement>
                <CheckboxElement
                    name="cna"
                    :columns="{ container:2, wrapper:12 }"
                    v-model="form.cna"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    CNA
                </CheckboxElement>
                <CheckboxElement
                    name="ssa"
                    :columns="{ container:2, wrapper:12 }"
                    v-model="form.ssa"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    SSA
                </CheckboxElement>
                <CheckboxElement
                    name="supervisar"
                    :columns="{ container:2, wrapper:12 }"
                    v-model="form.supervisar"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    Supervisar
                </CheckboxElement>
                <CheckboxElement
                    name="fecha_resultado_final"
                    :columns="{ container:2, wrapper:12 }"
                    v-model="form.fecha_resultado_final"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    Fecha resultados final
                </CheckboxElement>
                <CheckboxElement
                    name="parametro_campo"
                    :columns="{ container:2, wrapper:12 }"
                    v-model="form.parametro_campo"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    Parametro de campo
                </CheckboxElement>
                <CheckboxElement
                    name="incertidumbre"
                    :columns="{ container:2, wrapper:12 }"
                    v-model="form.incertidumbre"
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    Incertidumbre
                </CheckboxElement>
                <div class="col-span-8"></div>
                <button
                    class="py-1 px-2 rounded-md text-white bg-blue-500">
                    Editar
                </button>
            </Vueform>
        </div>
        
    </AuthenticatedLayout>
</template>