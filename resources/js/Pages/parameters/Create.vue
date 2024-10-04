<script setup>
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
import { ElementLayout } from '@vueform/vueform';
    const props = defineProps({
        errors: Object
    });
    const form = useForm({
        parametro: null,
    });
    const handleCreateSubmit = async(form$, FormData) => {
        form.parametro = form$.requestData.parametro;
        form.abreviacion = form$.requestData.abreviacion;
        form.arrange = form$.requestData.arrange;
        form.post('/parameters');
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-10/12 p-5 rounded-lg bg-gray-100">
           <CreateTitle 
                title="Crear un nuevo parametro"
                :own-link="route('parameters.create')"
                :back-link="route('parameters.index')"/>
            <Vueform
                :endpoint="false"
                :display-errors="false"
                @submit="handleCreateSubmit"
                :scroll-to-invalid="false"
                :columns="{container: 12, wrapper: 12}">
                <TextElement 
                    name="parametro"
                    before="Parametro"
                    :description="errors.parametro ? `<p class='text-red-500'>${errors.parametro}</p>`:null"
                    :columns="{container: 3, wrapper:12}"/>
                <TextElement 
                    name="abreviacion"
                    before="Abreviacion"
                    :description="errors.abreviacion ? `<p class='text-red-500'>${errors.abreviacion}</p>`:null"
                    :columns="{container: 3, wrapper:12}"/>
                <TextElement 
                    name="arrange"
                    before="Orden de acomodo"
                    input-type="number"
                    :description="errors.arrange ? `<p class='text-red-500'>${errors.arrange}</p>`:null"
                    :columns="{container: 3, wrapper:12}"/>
                <CheckboxElement
                    name="subcontratado"
                    :columns="{ container:3, wrapper:12 }"
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
                    :add-classes="{
                        ElementLayout: {
                            container: ['flex items-center']
                        }
                    }">
                    SSA
                </CheckboxElement>
                <ButtonElement
                    submits
                    name="submit">
                    Crear
                </ButtonElement>
            </Vueform>
        </div>
        
    </AuthenticatedLayout>
</template>