<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3';
import FinanceViewer from './FinanceComponents/FinanceViewer.vue';
import Button from '@/components/ui/button/Button.vue'
import FinanceAppsidebarLayout from '@/layouts/Finance/FinanceAppsidebarLayout.vue';
import Input from '@/components/ui/input/Input.vue';

const page = usePage()

const props = defineProps({
  application: {
    type: Array,
    required: true,
  },
  approver_status: {
    type: String,
    required: true,
  },
  group: {
    type: Object,
    required: true,
  }
})

// Modal State
const showModal = ref(false)
const showApprove = ref(false)
const OR_Number = ref('')
const actionType = ref(null) // "pay" or "return"

// APPROVE
const handleApprove = () => {
  router.post(
    `/application-for-approval/${props.application.form_number}/approvers/${page.props.auth.user.id}/approve`,
    {},
    {
      onSuccess: () => console.log('Application approved!'),
      onError: (errors) => console.error('Approval failed', errors),
    }
  );
};

// OPEN MODAL
const openModal = (type) => {
  actionType.value = type
  showModal.value = true
}

// CONFIRM ACTION (Pay or Return)
const confirmAction = () => {
    if(OR_Number != null){
      showApprove.value = true
    }
  console.log('Action: ', actionType.value)
  console.log('OR Number: ', OR_Number.value)

  // Reset modal
  showModal.value = false
  OR_Number.value = ''
};

// CANCEL / CLOSE MODAL
const cancelAction = () => {
  showModal.value = false
  OR_Number.value = ''
}
</script>

<template>
  <FinanceAppsidebarLayout>
    
    <FinanceViewer :application="props.application" :group="props.group" />
      
    <!-- ACTION BAR -->
    <div
      v-if="props.approver_status === 'Pending'"
      class="flex justify-center gap-4 mt-8"
    >
      <Button
      v-if="showApprove"
        variant="default"
        class="px-6 py-2 text-sm font-semibold"
        @click="handleApprove"
      >
        Approve
      </Button>

      <Button
        variant="destructive"
        class="px-6 py-2 text-sm font-semibold"
        @click="openModal('pay')"
      >
        Payment
      </Button>

      <Button
        class="px-6 py-2 text-sm font-semibold bg-green-600 text-white hover:bg-green-700"
        @click="openModal('return')"
      >
        Return
      </Button>
    </div>

    <!-- MODAL -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
    >
      <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-xl">
        
        <!-- TITLE -->
        <h2 class="text-lg font-semibold mb-3">
          {{ actionType === 'pay' ? 'Permit Payment' : '' }}
        </h2>
         <label class="text-sm font-medium">Total Amount:</label>
        <p class="text-md text-gray-800 font-bold">₱{{ props.application.selections[0].amount }}</p>
        <label class="text-sm font-medium">OR Number:</label>
        <Input
          v-model="OR_Number"
          type="text"
          class="w-full mt-2 border border-gray-300 rounded-lg p-3 text-sm focus:ring focus:ring-primary"
          placeholder="Enter OR Number..."
        />


        <!-- FOOTER -->
        <div class="mt-4 flex justify-end gap-3">
          <Button variant="secondary" @click="cancelAction">
            Cancel
          </Button>

          <Button
            :class="actionType === 'pay'
              ? 'bg-red-600 text-white hover:bg-red-700'
              : 'bg-green-600 text-white hover:bg-green-700'"
            @click="confirmAction"
          >
            {{ actionType === 'pay' ? 'Confirm Payment' : 'Confirm Payment' }}
          </Button>
        </div>
      </div>
    </div>

  </FinanceAppsidebarLayout>
</template>
