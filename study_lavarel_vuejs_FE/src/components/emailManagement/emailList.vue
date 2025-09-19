<template>
  <div>
    <!-- Loading State -->
    <div v-if="isLoading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Empty State -->
    <div v-else-if="emails.length === 0" class="text-center py-16">
      <div class="text-6xl mb-6 font-bold text-gray-400">NO EMAILS</div>
      <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">No emails found</h3>
      <p class="text-gray-500 dark:text-gray-400 text-lg">Get started by creating your first email.</p>
    </div>

    <!-- Email List -->
    <div v-else class="space-y-6">
      <div
        v-for="email in emails"
        :key="email.id"
        class="bg-white dark:bg-gray-800 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-blue-200 dark:hover:border-blue-600 group"
      >
        <div class="flex items-start justify-between">
          <div class="flex-1 min-w-0">
            <!-- Header with Subject and Status -->
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                {{ email.subject || 'No Subject' }}
              </h3>
              <span
                :class="{
                  'bg-gradient-to-r from-green-100 to-green-200 text-green-800 dark:from-green-900 dark:to-green-800 dark:text-green-300': email.status === 'sent',
                  'bg-gradient-to-r from-red-100 to-red-200 text-red-800 dark:from-red-900 dark:to-red-800 dark:text-red-300': email.status === 'failed',
                  'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 dark:from-yellow-900 dark:to-yellow-800 dark:text-yellow-300': email.status === 'pending'
                }"
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold shadow-sm"
              >
                <div class="w-2 h-2 rounded-full mr-2" :class="{
                  'bg-green-500': email.status === 'sent',
                  'bg-red-500': email.status === 'failed',
                  'bg-yellow-500': email.status === 'pending'
                }"></div>
                {{ email.status.toUpperCase() }}
              </span>
            </div>
            
            <!-- Email Info -->
            <div class="space-y-3 mb-4">
              <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                  <span class="text-blue-500 font-bold text-xs">EMAIL</span>
                  <span class="font-medium text-sm">:</span>
                  <span class="text-gray-900 dark:text-white font-mono text-sm">{{ email.email }}</span>
                </div>
              </div>
              
              <div v-if="email.content" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-2">
                  <span class="text-purple-500 font-bold text-xs">CONTENT</span>
                  <span class="font-medium text-gray-700 dark:text-gray-300 text-sm">:</span>
                </div>
                <p class="text-gray-700 dark:text-gray-300 line-clamp-3 leading-relaxed text-sm">
                  {{ email.content }}
                </p>
              </div>
            </div>

            <!-- Timestamp -->
            <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
              <span>Created at: {{ new Date(email.created_at).toLocaleString() }}</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center space-x-2 ml-4">
            <button
              @click="$emit('view', email)"
              class="px-3 py-2 text-blue-600 hover:text-white hover:bg-blue-600 dark:text-blue-400 dark:hover:bg-blue-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105 text-sm font-semibold"
              title="View Details"
            >
              VIEW
            </button>
            
            <button
              @click="$emit('edit', email)"
              class="px-3 py-2 text-green-600 hover:text-white hover:bg-green-600 dark:text-green-400 dark:hover:bg-green-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105 text-sm font-semibold"
              title="Edit Email"
            >
              EDIT
            </button>
            
            <button
              @click="$emit('delete', email)"
              class="px-3 py-2 text-red-600 hover:text-white hover:bg-red-600 dark:text-red-400 dark:hover:bg-red-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:scale-105 text-sm font-semibold"
              title="Delete Email"
            >
              DELETE
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getEmails } from '../../services/email.js'

const emails = ref([])
const isLoading = ref(true)
const emit = defineEmits(['view', 'edit', 'delete'])

const fetchEmails = async () => {
  try {
    isLoading.value = true
    const res = await getEmails()
    emails.value = res.data
  } catch (error) {
    console.error('Error fetching emails:', error)
    alert('Failed to fetch emails. Please try again.')
  } finally {
    isLoading.value = false
  }
}

onMounted(fetchEmails)

// Expose refresh method for parent component
defineExpose({
  refresh: fetchEmails
})
</script>
