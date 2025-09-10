<template>
  <div>
    <ul>
      <li v-for="e in emails" :key="e.id">
        {{ e.email }}
        <button @click="$emit('view', e)">View</button>
        <button @click="$emit('edit', e)">Edit</button>
        <button @click="$emit('delete', e)">Delete</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getEmails } from '../../services/email.js'

const emails = ref([])
const emit = defineEmits(['view', 'edit', 'delete'])

const fetchEmails = async () => {
  const res = await getEmails()
  emails.value = res.data
}

onMounted(fetchEmails)
</script>
