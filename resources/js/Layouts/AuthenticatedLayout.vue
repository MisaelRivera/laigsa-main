<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import { useImages } from '@/composables/images';
import { usePermission } from '@/composables/permissions';
const page = usePage();
const { getRoles } = usePermission();
const { getLogoUri } = useImages(); 
const userName = page.props.auth.user.name;
const isVisibleServiciosAguas = ref(false), 
      isVisibleServiciosAlimentos = ref(false), 
      isVisibleServiciosResiduos = ref(false); 
</script>

<template>
    <div class="px-24 py-2 bg-slate-200 flex justify-between items-center">
        <div class="flex items-center">
            <img 
                :src="getLogoUri()" 
                alt=""
                class="w-16 h-16 rounded-full mr-2">
            <h2>
                <Link :href="route('home')">
                    Laboratorio de Analisis Industriales del Guadiana S. A. de C. V.
                </Link>
            </h2>
        </div>
        <nav>
            <ul class="list-none flex items-center">
                <li class="text-green-600 text-md">
                    <Dropdown>
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-slate-200 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    Ordenes y resultados
                                    <svg
                                        class="ms-2 -me-0.5 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <template #content>
                            <template v-if="getRoles().includes('admin')">
                                <li 
                                    class="text-center text-sm">
                                    <Link :href="route('orders.index')">
                                        Ordenes
                                    </Link>
                                </li>
                                <li 
                                    class="text-center text-sm">
                                    <Link :href="route('water_samples_results.index')">
                                        Resultados
                                    </Link>
                                </li>
                                <li 
                                    class="text-center text-sm">
                                    <Link :href="route('vue_form_test.test2')">
                                        Vue form
                                    </Link>
                                </li>
                                <li 
                                    class="text-center text-sm">
                                    <Link :href="route('users.index')">
                                        Administracion de usuarios
                                    </Link>
                                </li>
                                <li 
                                    class="text-center text-sm">
                                    <Link :href="route('params_description.index')">
                                        Descripcion de parametros
                                    </Link>
                                </li>
                                <li 
                                    class="text-center text-sm"
                                    @click="isVisibleServiciosAlimentos = true">
                                    Análisis de alimentos
                                </li>
                                <li 
                                    class="text-center text-sm"
                                    @click="isVisibleServiciosResiduos = true">
                                    Análisis de residuos
                                </li>
                                <!--<DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </DropdownLink>-->
                            </template>
                            <template v-else-if="getRoles().includes('analist') || getRoles().includes('sampler')">
                                <li 
                                    class="text-center text-sm">
                                    <Link :href="route('orders.index')">
                                        Ordenes
                                    </Link>
                                </li>
                                <li 
                                    class="text-center text-sm">
                                    <Link :href="route('water_samples_results.index')">
                                        Resultados
                                    </Link>
                                </li>
                            </template>
                        </template>
                    </Dropdown>
                </li>
                <li>
                    <Dropdown>
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-slate-200 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    Catalogos
                                    <svg
                                        class="ms-2 -me-0.5 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <template #content>
                            <li 
                                class="text-center text-sm">
                                <Link :href="route('clients.index')">Clientes</Link>
                            </li>
                            <li 
                                class="text-center text-sm">
                                <Link :href="route('rules.index')">
                                    Normas
                                </Link>
                            </li>
                            <li 
                                class="text-center text-sm">
                                <Link :href="route('units.index')">
                                    Unidades
                                </Link>
                            </li>
                            <li 
                                class="text-center text-sm">
                                <Link :href="route('tasks.index')">
                                    Tasks
                                </Link>
                            </li>
                            <li 
                                class="text-center text-sm">
                                <Link :href="route('methods.index')">
                                    Metodos
                                </Link>
                            </li>
                            <li 
                                class="text-center text-sm">
                                <Link :href="route('lcps.index')">
                                    Lcps
                                </Link>
                            </li>
                            <li 
                                class="text-center text-sm">
                                <Link :href="route('parameters.index')">Parametros</Link>
                            </li>
                            <li 
                                class="text-center text-sm">
                                <Link :href="route('parameters-combinations.index')">Combinacion de parametros</Link>
                            </li>
                        </template>
                    </Dropdown>
                </li>
                <li class="text-green-600 text-md mr-2">
                    {{ userName }}
                </li>
                <li class="text-green-600 text-md">
                    <Link 
                        :href="route('logout')" 
                        as="button" 
                        method="delete" 
                        class="text-sm">
                        Logout
                    </Link>
                </li>
            </ul>
        </nav>
    </div>
    <main>
        <div class="mx-auto w-11/12 my-10">
            <slot></slot>
        </div>
    </main>
</template>
