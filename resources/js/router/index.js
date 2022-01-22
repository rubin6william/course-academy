import {createRouter, createWebHistory} from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'Report',
        component: () => import('../views/Report')
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
