<template>
  <form @submit.prevent="submitEmail" class="space-y-6">
    <!-- Email -->
    <div class="space-y-2">
      <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
        <span class="flex items-center">
          Email Address
        </span>
      </label>
      <input
        v-model="email"
        type="email"
        placeholder="Enter email address"
        required
        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200 shadow-sm hover:shadow-md"
      />
    </div>

    <!-- Subject -->
    <div class="space-y-2">
      <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
        <span class="flex items-center">
          Subject
        </span>
      </label>
      <input
        v-model="subject"
        type="text"
        placeholder="Enter email subject"
        required
        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200 shadow-sm hover:shadow-md"
      />
    </div>

    <!-- Content -->
    <div class="space-y-2">
      <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
        <span class="flex items-center">
          Message Content
        </span>
      </label>
      <textarea
        v-model="content"
        placeholder="Enter your message content here..."
        required
        rows="6"
        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200 shadow-sm hover:shadow-md resize-none"
      ></textarea>
    </div>

    <!-- Submit Button -->
    <button
      type="submit"
      :disabled="isLoading"
      class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105 disabled:transform-none disabled:scale-100"
    >
      <span v-if="isLoading" class="animate-spin mr-3">⏳</span>
      {{ isLoading ? 'Processing...' : (isEdit ? 'Update Email' : 'Send Email') }}
    </button>
  </form>
</template>

<script setup>
import { ref, watch } from 'vue'
import { createEmail, updateEmail } from '../../services/email.js'

const props = defineProps({
  emailData: Object,   // pass khi edit
  isEdit: Boolean
})
const emit = defineEmits(['saved'])

const email = ref('')
const subject = ref('')
const content = ref('')
const isLoading = ref(false)

watch(() => props.emailData, (newVal) => {
  if (newVal) {
    email.value = newVal.email || ''
    subject.value = newVal.subject || ''
    content.value = newVal.content || ''
  }
}, { immediate: true })

const submitEmail = async () => {
  if (isLoading.value) return
  
  isLoading.value = true
  try {
    const emailData = {
      email: email.value,
      subject: subject.value,
      content: content.value
    }
    
    if (props.isEdit) {
      await updateEmail(props.emailData.id, emailData)
    } else {
      await createEmail(emailData)
    }
    emit('saved')
    resetForm()
  } catch (err) {
    console.error(err)
    alert('An error occurred. Please try again.')
  } finally {
    isLoading.value = false
  }
}

const resetForm = () => {
  email.value = ''
  subject.value = ''
  content.value = ''
}
</script>