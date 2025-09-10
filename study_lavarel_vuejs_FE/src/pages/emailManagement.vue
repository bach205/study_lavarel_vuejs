<template>
  <h2>Email Management</h2>
  <EmailForm @saved="refreshList" :emailData="editEmail" :isEdit="isEdit" />
  <EmailList @view="viewEmail" @edit="editEmailFn" @delete="deleteEmailFn" />
  <EmailView :emailData="viewedEmail" />
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

const refreshList = () => { editEmail.value = null; isEdit.value = false }

const viewEmail = (email) => { viewedEmail.value = email }
const editEmailFn = (email) => { editEmail.value = email; isEdit.value = true }
const deleteEmailFn = async (email) => {
  if (confirm('Are you sure?')) {
    await deleteEmail(email.id)
    refreshList()
  }
}
</script>
