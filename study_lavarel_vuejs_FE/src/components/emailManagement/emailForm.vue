<template>
  <form @submit.prevent="submitEmail">
    <input v-model="email" type="email" placeholder="Enter email" required />
    <button type="submit">{{ isEdit ? 'Update' : 'Submit' }}</button>
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

watch(() => props.emailData, (newVal) => {
  if (newVal) email.value = newVal.email
}, { immediate: true })

const submitEmail = async () => {
  try {
    if (props.isEdit) {
      await updateEmail(props.emailData.id, { email: email.value })
    } else {
      await createEmail({ email: email.value })
    }
    emit('saved')
    email.value = ''
  } catch (err) {
    console.error(err)
  }
}
</script>