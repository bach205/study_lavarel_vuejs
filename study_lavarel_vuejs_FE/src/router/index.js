import { createRouter, createWebHistory } from 'vue-router'
import EmailManagement from '../pages/emailManagement.vue'

const routes = [
    {
        path: '/',
        name: 'EmailManagement',
        component: EmailManagement
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router