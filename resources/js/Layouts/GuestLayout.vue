<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import { useImages } from '@/composables/images';
const { getLogoUri, getBgUri } = useImages(); 
const isVisibleServiciosAguas = ref(false), 
      isVisibleServiciosAlimentos = ref(false), 
      isVisibleServiciosResiduos = ref(false); 
const page = usePage();
      if (page.url === '/') {
        document.body.classList.add('indexBody');
        document.body.style.backgroundImage = getBgUri();
    } else {
        document.body.classList.remove('indexBody');
        document.body.style.backgroundImage = '';
    }
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
                    <Link :href="route('us')" class="text-sm">Nosotros</Link>
                </li>
                <li>
                    <Dropdown>
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-slate-200 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    Servicios
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
                                class="text-center text-sm"
                                @click="isVisibleServiciosAguas = true">
                                Análisis de aguas
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
                    </Dropdown>
                </li>
                <li class="text-green-600 text-md mr-2">
                    <Link :href="route('contact')" class="text-sm">
                        Contacto
                    </Link>
                </li>
                <li class="text-green-600 text-md">
                    <Link :href="route('login')" class="text-sm">
                        Login
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
