<!-- src/components/emailManagement/EmailView.vue -->
<template>
  <!-- chỉ show khi modal được bật -->
  <div v-if="isVisible" class="fixed inset-0 z-50 overflow-y-auto" @click.self="emitClose">
    <div class="flex items-center justify-center min-h-screen px-4 py-8 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="emitClose"></div>

      <!-- show loading nếu chưa có email, tránh lỗi null access -->
      <div v-if="!email" class="inline-block bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl sm:max-w-lg sm:w-full">
        <div class="text-center">Loading...</div>
      </div>

      <!-- panel chính (chỉ render khi có email) -->
      <div v-else
        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
        role="dialog" aria-modal="true" aria-labelledby="modal-title"
      >
        <!-- Header -->
        <div class="px-6 py-4 border-b dark:border-gray-700">
          <div class="flex items-center justify-between">
            <h3 id="modal-title" class="text-2xl font-semibold text-gray-900 dark:text-white">
              Email Details
            </h3>
            <button @click="emitClose" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl font-bold">✕</button>
          </div>
        </div>

        <!-- Content -->
        <div class="px-8 py-8">
          <!-- Status badge -->
          <div class="flex items-center justify-center mb-6">
            <span
              :class="statusBadgeClass"
              class="inline-flex items-center px-5 py-2 rounded-full text-sm font-semibold"
            >
              <span class="w-2 h-2 rounded-full mr-3" :class="statusDotClass"></span>
              {{ (email.status || 'unknown').toUpperCase() }}
            </span>
          </div>

          <!-- Subject -->
          <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ email.subject || 'No Subject' }}</h2>
          </div>

          <!-- Email Address -->
          <div class="mb-6 rounded-2xl p-4 border">
            <div class="mb-2 text-sm font-semibold text-gray-600 dark:text-gray-300">EMAIL Address</div>
            <p class="font-mono text-lg text-gray-900 dark:text-white break-words">{{ email.email || '-' }}</p>
          </div>

          <!-- Content -->
          <div class="mb-6 rounded-2xl p-4 border">
            <div class="mb-2 text-sm font-semibold text-gray-600 dark:text-gray-300">Message</div>
            <div class="prose dark:prose-invert max-w-none">
              <p class="whitespace-pre-wrap text-gray-900 dark:text-white leading-relaxed">{{ email.content || 'No content available' }}</p>
            </div>
          </div>

          <!-- Timestamps -->
          <div class="rounded-2xl p-4 border">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="text-center">
                <div class="text-xs font-bold text-gray-600">CREATED AT</div>
                <div class="font-mono text-sm text-gray-900 dark:text-white">{{ formatDate(email.created_at) }}</div>
              </div>
              <div v-if="email.updated_at" class="text-center">
                <div class="text-xs font-bold text-gray-600">UPDATED AT</div>
                <div class="font-mono text-sm text-gray-900 dark:text-white">{{ formatDate(email.updated_at) }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-6 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
          <div class="flex justify-center">
            <button @click="emitClose" class="px-6 py-2 rounded-lg bg-white dark:bg-gray-800 border hover:shadow-md">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  emailData: { type: Object, default: null },
  isVisible: { type: Boolean, default: false }
})
const emit = defineEmits(['close'])

// computed unwraps safely
const email = computed(() => props.emailData ?? null)

const emitClose = () => emit('close')

// helper
const formatDate = (d) => {
  if (!d) return '-'
  try { return new Date(d).toLocaleString() } catch { return String(d) }
}

// classes reactive based on status
const statusBadgeClass = computed(() => {
  switch ((email.value && email.value.status) || '') {
    case 'sent': return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
    case 'failed': return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
    case 'pending': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
    default: return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200'
  }
})
const statusDotClass = computed(() => {
  switch ((email.value && email.value.status) || '') {
    case 'sent': return 'bg-green-500'
    case 'failed': return 'bg-red-500'
    case 'pending': return 'bg-yellow-500'
    default: return 'bg-gray-400'
  }
})
</script>
