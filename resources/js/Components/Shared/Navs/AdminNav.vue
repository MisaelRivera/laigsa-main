<script setup>
    import { h,  ref } from 'vue';
    import { usePage, Link } from '@inertiajs/vue3';
    import { DownOutlined } from '@ant-design/icons-vue';
    const page = usePage();
    const current = ref(['nosotros']);
</script>
<template>
    <header class="px-28 py-2 bg-slate-200 flex justify-between align-center">
        <h1 class="ml-3 font-slab font-semibold text-emerald-700">
            <img 
                :src="page.props.images.logoUrl"
                alt="Imagen del logo"
                class="h-12 xl:h-16 w-12 xl:w-16 rounded-full bg-white inline-block">
            <Link href="/">Laboratorio de Analisis Industriales del Guadiana S. A. de C. V.</Link>
        </h1>
        <div class="w-60">
            <a-menu 
                v-model:selectedKeys="current"
                :class="['bg-inherit']"
                mode="horizontal">
                <a-menu-item key="ordenes_resultados">
                    <a-dropdown>
                        <a-button :style="{backgroundColor: 'inherit', borderColor: 'transparent', boxShadow: 'none'}">
                            Ordenes y resultados <DownOutlined/>
                        </a-button>
                        <template #overlay>
                            <a-menu>
                                <a-menu-item>
                                    <Link href="/orders">Ordenes</Link>
                                </a-menu-item>
                                <a-menu-item>
                                    <Link href="/results">Resultados</Link>
                                </a-menu-item>
                            </a-menu>
                        </template>
                    </a-dropdown>
                </a-menu-item>
                <a-menu-item key="catalogos" :disabled="true">
                    <a-dropdown>
                        <a-button :style="{backgroundColor: 'inherit', borderColor: 'transparent', boxShadow: 'none'}">
                            Catalogos <DownOutlined/>
                        </a-button>
                        <template #overlay>
                            <a-menu>
                                <a-menu-item>
                                    <Link href="/clients">Clientes</Link>
                                </a-menu-item>
                                <a-menu-item>
                                    <Link href="/rules">Normas</Link>
                                </a-menu-item>
                                <a-menu-item>
                                    <Link href="/parameters">Parametros</Link>
                                </a-menu-item>
                                <a-menu-item>
                                    <Link href="/units">Unidades</Link>
                                </a-menu-item>
                                <a-menu-item>
                                    <Link href="/methods">Metodos</Link>
                                </a-menu-item>
                                <a-menu-item>
                                    <Link href="/orders-info">Ordenes info</Link>
                                </a-menu-item>
                            </a-menu>
                        </template>
                    </a-dropdown>
                </a-menu-item>
                <a-menu-item key="extras">
                    <a-dropdown>
                        <a-button :style="{backgroundColor: 'inherit', borderColor: 'transparent', boxShadow: 'none'}">
                            Extras <DownOutlined/>
                        </a-button>
                        <template #overlay>
                            <a-menu>
                                <a-menu-item>
                                    <Link href="/cesavedac">Cesavedac</Link>
                                </a-menu-item>
                                <a-menu-item>
                                    <Link href="/polls">Encuestas</Link>
                                </a-menu-item>
                            </a-menu>
                        </template>
                    </a-dropdown>
                </a-menu-item>
                <a-menu-item key="logout">
                    <Link href="/logout">
                        Cerrar sesion
                    </Link>
                </a-menu-item>
            </a-menu>
        </div>
    </header>
    
</template>

