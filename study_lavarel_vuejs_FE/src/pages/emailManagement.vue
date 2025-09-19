<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-8">
      <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in-up flex flex-col justify-center align-center">
          <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">
            Email Dashboard
          </h1>
          <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
            Manage your email communications efficiently
          </p>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Email Form -->
          <div class="lg:col-span-1 animate-slide-in-right">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 sticky top-8 border border-gray-100 dark:border-gray-700 card-hover">
              <div class="flex items-center space-x-3 mb-8">
                <div class="px-3 py-3 w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                  <span class="text-white font-bold text-xs">{{ isEdit ? 'EDIT' : 'NEW' }}</span>
                </div>
              </div>
              <EmailForm @saved="refreshList" :emailData="editEmail" :isEdit="isEdit" />
            </div>
          </div>

          <!-- Email List -->
          <div class="lg:col-span-2 animate-fade-in-up">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 border border-gray-100 dark:border-gray-700 card-hover">
              <div class="flex items-center justify-between mb-8">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                  Manage your emails
                </div>
              </div>
              <EmailList ref="emailListRef" @view="viewEmail" @edit="editEmailFn" @delete="deleteEmailFn" />
            </div>
          </div>
        </div>

        <!-- Email View Modal -->
        <EmailView 
          :emailData="viewedEmail" 
          :isVisible="isViewVisible"
          @close="closeView" 
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import EmailForm from '../components/emailManagement/EmailForm.vue'
import EmailList from '../components/emailManagement/EmailList.vue'
import EmailView from '../components/emailManagement/EmailView.vue'
import { deleteEmail } from '../services/email'

const editEmail = ref(null)
const viewedEmail = ref(null)
const isEdit = ref(false)
const isViewVisible = ref(false)
const emailListRef = ref(null)

const refreshList = () => { 
  editEmail.value = null; 
  isEdit.value = false
  if (emailListRef.value) {
    emailListRef.value.refresh()
  }
}

const viewEmail = (email) => { 
  viewedEmail.value = email
  isViewVisible.value = true
}
const editEmailFn = (email) => { editEmail.value = email; isEdit.value = true }
const closeView = () => { 
  isViewVisible.value = false
  viewedEmail.value = null
}
const deleteEmailFn = async (email) => {
  if (confirm('Are you sure you want to delete this email?')) {
    try {
      await deleteEmail(email.id)
      refreshList()
    } catch (error) {
      console.error('Error deleting email:', error)
      alert('Failed to delete email. Please try again.')
    }
  }
}
</script>
