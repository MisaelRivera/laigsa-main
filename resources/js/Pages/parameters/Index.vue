<script setup>
    import { ref, h } from 'vue';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
        parametersProp: Object
    }); 
    const { getMessage } = useMessages();
    const parameters = props.parametersProp.data.map((item) =>  {
        const len = item.parametro.length;
        
        return { parameter: item.parametro,
             longitud: h('div', {
                    class: ['w-8', 'h-8', 'rounded-full', {'bg-blue-400': len < 15, 'bg-red-500': len >= 15}],
                }) };
    });
    const columns = [
        {
            title: 'Parametro',
            dataIndex: 'parameter'
        },
        {
            title: 'longitud',
            dataIndex: 'longitud',
        }
    ];
    console.log(parameters);
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto">
            <a-alert
                type="success"
                v-if="getMessage()"
                :message="getMessage()">
            </a-alert>
            <!--<table class="border">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border"></th>
                        <th class="py-2 px-4 border"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="parameter in parameters">
                        <td class="py-2 px-4 border">
                            {{ parameter.parametro }}
                        </td>
                        <td class="py-2 px-4 border">
                            <button class="bg-blue-400 text-white py-2 px-4 rounded">Editar</button>
                        </td>
                    </tr>
                </tbody>
            </table>-->
            <a-table 
                bordered 
                :data-source="parameters"
                :columns="columns"/>
        </div>
    </AuthenticatedLayout>
</template>