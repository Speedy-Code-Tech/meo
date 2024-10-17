<script setup>
import AuthenticatedLayout from '../../../../Admin/Layouts/AuthenticatedLayout.vue';
defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
  totals: { type: Object, required: true },
  totalsToday: { type: Object, required: true },
});
</script>

<template>
  <div class="card">
    <h2 class="font-bold text-lg text-typography leading-tight mb-2">Dashboard</h2>
    <div class="flex">
      <div class="flex-1 overflow-hidden rounded-lg shadow-lg m-2 p-6 bg-sky-500">
        <p class="text-center text-white mt-2">Business Permit</p>
        <p class="text-center text-white mt-2">Total: {{ totals.businessPermit }}</p>
        <p class="text-center text-white mt-2">Today: {{ totalsToday.businessPermit }}</p>
      </div>
      <div class="flex-1 overflow-hidden rounded-lg shadow-lg m-2 p-6 bg-blue-800">
        <p class="text-center text-white mt-2">Building Permit</p>
        <p class="text-center text-white mt-2">Total: {{ totals.building }}</p>
        <p class="text-center text-white mt-2">Today: {{ totalsToday.building }}</p>
      </div>
      <div class="flex-1 overflow-hidden rounded-lg shadow-lg m-2 p-6 bg-red-800">
        <p class="text-center text-white mt-2">Business Permit Renewal</p>
        <p class="text-center text-white mt-2">Total: {{ totals.renewal }}</p>
        <p class="text-center text-white mt-2">Today: {{ totalsToday.renewal }}</p>
      </div>
    </div>
  </div>
</template>
