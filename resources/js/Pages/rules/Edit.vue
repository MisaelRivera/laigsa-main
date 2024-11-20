<script setup>
    import { ref, onMounted } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import CreateTitle from '@/Components/Shared/CreateTitle.vue';
    const props = defineProps({

        rule: {
            type: Object,
        },

        page: {
            type: String
        }
    });
    const form$ = ref(null);
    const form = useForm({
        norma: props.rule.norma,
        tipo: props.rule.tipo,
        descripcion: props.rule.descripcion,
        cesavedac: props.rule.cesavedac,
        aguas: props.rule.aguas,
    });
    const handleEditSubmit = () => {
        form.norma = form$.value.el$('norma').value;
        form.tipo = form$.value.el$('tipo').value;
        form.descripcion = form$.value.el$('descripcion').value;
        form.cesavedac = form$.value.el$('cesavedac').value;
        form.aguas = form$.value.el$('aguas').value;
        form.put(`/rules/${props.rule.id}`);
    };
    
    onMounted(() => {
        const norma = form$.value.el$('norma');
        const tipo = form$.value.el$('tipo');
        const descripcion = form$.value.el$('descripcion');
        const cesavedac = form$.value.el$('cesavedac');
        const aguas = form$.value.el$('aguas');
        norma.update(props.rule.norma);
        tipo.update(props.rule.tipo);
        descripcion.update(props.rule.descripcion);
        cesavedac.update(props.rule.cesavedac);
        aguas.update(props.rule.aguas);
    });
</script>
<template>
    <AuthenticatedLayout>
        <div class="mx-auto w-10/12 p-5 rounded-lg bg-gray-100">
            <CreateTitle 
                title="Editar la norma"
                :back-link="route('rules.index', { page: page })"
                :own-link="route('rules.edit', { rule: props.rule, page: page })"/>
                <Vueform
                :endpoint="false"
                @submit="handleEditSubmit"
                ref="form$"
                :columns="{container:12, wrapper:12}">
                <TextElement
                    :columns="{container:6, wrapper:12}"
                    name="norma"
                    before="Norma">
                    <template #description>
                        <p 
                            class="text-red-500"
                            v-if="form.errors.norma">
                            {{ form.errors.norma }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="tipo"
                    before="Tipo"
                    :columns="{container:6, wrapper:12}">
                    <template #description>
                        <p 
                            class="text-red-500"
                            v-if="form.errors.tipo">
                            {{ form.errors.tipo }}
                        </p>
                    </template>
                </TextElement>
                <TextElement
                    name="descripcion"
                    before="Descripcion"
                    :columns="{container:6, wrapper:12}">
                    <template #description>
                        <p 
                            class="text-red-500"
                            v-if="form.errors.descripcion">
                            {{ form.errors.descripcion }}
                        </p>
                    </template>
                </TextElement>
                <CheckboxElement
                    name="cesavedac"
                    class="mt-8"
                    :columns="{container:3, wrapper:12}">
                    Cesavedac
                </CheckboxElement>
                <CheckboxElement
                    name="aguas"
                    class="mt-8"
                    :columns="{container:3, wrapper:12}">
                    Aguas
                </CheckboxElement>
                <button class="rounded py-3 px-2 bg-blue-500 text-white">Editar</button>
            </Vueform>
        </div>
    </AuthenticatedLayout>
</template>