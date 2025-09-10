<template>
  <div v-if="email">
    <h3>Email Details</h3>
    <p>ID: {{ email.id }}</p>
    <p>Email: {{ email.email }}</p>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  emailData: Object
})

const email = ref(null)
watch(() => props.emailData, (val) => email.value = val, { immediate: true })
</script>
