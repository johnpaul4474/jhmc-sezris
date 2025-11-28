<script setup>
import OsacAppSidebarLayout from '@/layouts/Osac/OsacAppSidebarLayout.vue'
import OsacViewer from './OsacComponents/OsacViewer.vue'
import Button from '@/components/ui/button/Button.vue'

const props = defineProps({
  application: {
    type: Array,
    required: true,
  },
  approver_status: {
    type: String,
    required: true,
  }
})

const handleApprove = () => {
  console.log("Approved:", props.application)
}

const handleReject = () => {
  console.log("Rejected:", props.application)
}
</script>

<template>
  <OsacAppSidebarLayout>
    <OsacViewer :application="props.application" />

    <!-- Professional Action Bar -->
    <div 
      v-if="props.approver_status === 'Pending'"
      class="flex justify-center gap-4 mt-8"
    >
      <Button
        variant="default"
        class="px-6 py-2 text-sm font-semibold"
        @click="handleApprove"
      >
        Approve
      </Button>

      <Button
        variant="destructive"
        class="px-6 py-2 text-sm font-semibold"
        @click="handleReject"
      >
        Reject
      </Button>
    </div>
  </OsacAppSidebarLayout>
</template>
