<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import axios from 'axios';
    const props = defineProps({
        orders: {
            type: Object
        }
    });
    const propOrders = ref(props.orders);
    const folioSearch = ref(null);
    const clientSearch = ref(null);
    const handleFilters = async () => {
        let queryStr = '';
        if (folioSearch.value) {
            queryStr += `folio=${folioSearch.value}`;
        }

        if (clientSearch.value) {
            if (queryStr) {
                queryStr += `&cliente=${clientSearch}`;
            } else {
                queryStr += `cliente=${clientSearch}`;
            }
        }
        const url = '/filters?' + queryStr;
        try {
            const res = await axios.get(url);
            console.log(res);
           // propOrders.value =res.data;
        } catch(e) {
            console.log(e);
        }
    };
</script>
<template>
    <GuestLayout>
        <div class="w-5/12 mx-auto my-2">
            <table>
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-[1px] border-slate-200 w-40">
                            Folio 
                            <input 
                                class="font-normal border-[1px] border-slate-400 w-16 inline-block focus:outline-0 focus:border-blue-300"
                                v-model="folioSearch"
                                @input="handleFilters"
                                type="number">
                        </th>
                        <th class="px-4 py-2 border-[1px] border-slate-200">
                            Cliente
                            <input
                                class="font-normal border-[1px] border-slate-400 w-40 inline-block focus:outline-0 focus:border-blue-300"
                                type="text" 
                                @input="handleFilters"
                                v-model="clientSearch"/>
                        </th>
                        <th class="px-4 py-2 border-[1px] border-slate-200"></th>
                        <th class="px-4 py-2 border-[1px] border-slate-200">Fecha recepcion</th>
                        <th class="px-4 py-2 border-[1px] border-slate-200">Hora recepcion</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="order in propOrders"
                        :key="order.id">
                        <td class="px-4 py-2 border-[1px] border-slate-200">{{ order.folio }}</td>
                        <td class="px-4 py-2 border-[1px] border-slate-200">{{ order.cliente.cliente || order.cliente }}</td>
                        <td class="px-4 py-2 border-[1px] border-slate-200">
                            <div 
                                :class="[order.color_aguas]"
                                class="h-8 w-8 rounded-full"></div>
                        </td>
                        <td class="px-4 py-2 border-[1px] border-slate-200">{{ order.fecha_recepcion }}</td>
                        <td class="px-4 py-2 border-[1px] border-slate-200">{{ order.hora_recepcion }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </GuestLayout>
</template>
<style>
    .blue {
        background-color: blue;
    }

    .red {
        background-color: red;
    }
</style>
