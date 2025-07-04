<template>
  <div class="overflow-x-auto bg-white rounded-lg shadow p-4">
    <table class="min-w-full divide-y divide-gray-200">
      <thead>
        <tr class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Client</th>
          <th class="px-4 py-2">CESAVEDAC</th>
          <th class="px-4 py-2">Type</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <tr v-for="order in orders" :key="order.id">
          <td class="px-4 py-2">{{ order.id }}</td>
          <td class="px-4 py-2">{{ order.client_name || '—' }}</td>
          <td class="px-4 py-2">
            <span
              :class="order.cesavedac ? 'text-green-700 font-semibold' : 'text-gray-500'"
            >
              {{ order.cesavedac ? 'Yes' : 'No' }}
            </span>
          </td>
          <td class="px-4 py-2 capitalize">{{ order.type || '—' }}</td>
        </tr>
      </tbody>
    </table>
    <div v-if="!orders.length" class="text-center text-gray-500 mt-4">No orders found.</div>
  </div>
</template>

<script setup>
defineProps({ orders: Array })
</script>