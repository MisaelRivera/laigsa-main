<script setup>
    import { router } from '@inertiajs/vue3';
    import { useMessages } from '@/composables/messages';
    import AuthenticatedLayout from 'AuthenticatedLayout.vue';
    import IndexTitle from '@/Components/Shared/IndexTitle.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    const props = defineProps({
        rules: {
            type: Array,
        },
        filters: Object
    });


    const { getMessage } = useMessages();
    const handleFilter = (ev) => {
        const value = ev.target.value;
        router.visit(route('rules.index', { byRule: value }), {
            preserveState: true,
            method: 'get'
        });
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="w-10/12 mx-auto">
            <div class="flex justify-between items-center">
                <IndexTitle 
                    title="Normas"
                    :add-link="route('rules.create')"
                    :own-link="route('rules.index')"/>
                
                <Pagination 
                    :links="rulesProp.links"/>
                <div 
                    class="flex items-center">
                    <div class="w-40 mb-4 mr-3">
                        <label for="filtro">Filtro</label>
                        <input 
                            type="text"
                            id="filtro"
                            name="filter"
                            class="h-8 w-40 rounded"
                            v-model="filters.byRule"
                            @input="handleFilter">
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>