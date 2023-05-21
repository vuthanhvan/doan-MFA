<script setup>
import axios from "axios"
import { useRoute, useRouter } from 'vue-router'
import { setCookie, getCookie, removeCookie } from '../utils/cookie'
import { reactive, ref } from 'vue'
import Loading from '../components/Loading.vue'

const { push } = useRouter()
const user = reactive(JSON.parse(getCookie('user') ?? "{}"))
const loading = ref(false)

if (user?.token && user.token.length) {
    loading.value = true
    axios.get("http://localhost:8000/api/user", {
        headers: {
            "Authorization": "Bearer " + user.token,
        }
    }).then(response => {
        setCookie("user", JSON.stringify(response.data))
        push('/')
    }).catch(err => {
        removeCookie('user')
        push('/login')
    }).finally(() => loading.value = false)
} else {
    removeCookie('user')
    push('/login')
}

const logout = () => {
    removeCookie('user')
    push('/login')
}
</script>

<template>
    <Loading class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" v-if="loading" />

    <div v-else-if="user?.user?.email" class="max-w-7xl mx-auto p-4 sm:px-6 lg:px-8">
        <h2 class="text-lg font-bold uppercase mb-2 inline-block">User information</h2>
        <table>
            <tr>
                <td class="pr-4">Name</td>
                <td class="pl-4">{{ user.user.name }}</td>
            </tr>
            <tr>
                <td class="pr-4">Email</td>
                <td class="pl-4">{{ user.user.email }}</td>
            </tr>
            <tr>
                <td class="pr-4">Created at</td>
                <td class="pl-4">{{ (new Date(user.user.created_at)).toString() }}</td>
            </tr>
        </table>
        <button @click="logout"
            class="mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Logout</button>
    </div>
</template>