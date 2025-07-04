<template>
  <div class="mt-4 flex justify-center space-x-1 text-sm">
    <template v-for="(link, i) in links" :key="i">
      <button
        v-if="link.url"
        :disabled="link.active"
        @click="go(link.url)"
        class="px-3 py-1 border rounded"
        :class="[
          link.active ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100',
          link.label.includes('Next') || link.label.includes('Previous') ? 'font-bold' : '',
        ]"
        v-html="link.label"
      />
      <span v-else class="px-3 py-1 text-gray-400" v-html="link.label"></span>
    </template>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

defineProps({ links: Array })

function go(url) {
  router.visit(url, { preserveState: true, replace: true })
}
</script>
