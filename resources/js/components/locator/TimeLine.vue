<script setup>
const props = defineProps({
  data: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <div class="p-4 bg-white shadow rounded">
    <h2 class="text-lg font-bold mb-4 text-gray-800">Approvers Timeline</h2>

    <div class="flex items-center justify-between overflow-x-auto py-6 px-2">
      <div
        v-for="(approver, index) in props.data"
        :key="approver.id"
        class="flex flex-col items-center relative min-w-[140px]"
      >
        <!-- Connector line -->
        <div
          v-if="index !== props.data.length - 1"
          class="absolute top-[18px] left-1/2 w-full h-1 bg-gray-300 z-0 translate-x-1/2"
        ></div>

        <!-- Step circle -->
        <div
          class="flex items-center justify-center w-9 h-9 rounded-full z-10 border-4 border-white text-white font-bold text-sm"
          :class="{
            'bg-green-500': approver.pivot.status === 'Approved',
            'bg-yellow-400': approver.pivot.status === 'Pending',
            'bg-red-500': approver.pivot.status === 'Rejected',
            'bg-gray-400': !approver.pivot.status,
          }"
        >
          {{ index + 1 }}
        </div>

        <!-- Approver Info -->
        <div class="text-center mt-3 w-full px-2">
          <p class="font-semibold text-gray-700 truncate">{{ approver.name }}</p>
          <p class="text-sm text-gray-500 capitalize">
            {{ approver.pivot.status || '—' }}
          </p>
          <p
            v-if="approver.pivot.acted_at"
            class="text-xs text-gray-400"
          >
            {{ new Date(approver.pivot.acted_at).toLocaleString() }}
          </p>
        </div>
      </div>
    </div>

    <div
      v-if="!props.data.length"
      class="text-gray-500 text-sm italic text-center py-4"
    >
      No approvers found.
    </div>
  </div>
</template>

<style scoped>
/* Slight tweak for connector positioning */
div[role='timeline'] {
  display: flex;
  justify-content: space-between;
  position: relative;
}
</style>
