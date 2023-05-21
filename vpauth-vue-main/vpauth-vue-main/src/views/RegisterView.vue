<script setup>
import { RouterLink, useRouter } from 'vue-router'
import { getCookie, setCookie } from '../utils/cookie'
import NextcloudSignIn from '../components/NextcloudSignIn.vue'
import Loading from '../components/Loading.vue'
import { ref } from 'vue'
import { useField, Field, Form, ErrorMessage } from "vee-validate"
import * as yup from 'yup'
import axios from "axios"

const { push } = useRouter()
const user = JSON.parse(getCookie('user') ?? "{}")
const loading = ref(false)
const errorMessage = ref('')

if (user?.token && user.token.length) push('/')

const validationSchema = yup.object({
    name: yup.string().required().label("Name"),
    email: yup.string().required().email().label("Email"),
    password: yup.string().required().label("Password"),
    password_confirmation: yup.string().required().label("Confirm password").oneOf([yup.ref('password')], "Passwords don't match!"),
})

const onSubmit = (values) => {
    loading.value = true
    axios.post("http://localhost:8000/api/signup", values).then(response => {
        setCookie("user", JSON.stringify(response.data))
        push('/')
    }).catch(err => {
        errorMessage.value = err.message
        if (err?.response?.data?.errors) errorMessage.value = err.response.data.errors[Object.keys(err?.response?.data?.errors)[0]][0]
        if (err?.response?.data?.msg) errorMessage.value = err.response.data.msg
    }).finally(() => loading.value = false)
}
</script>
<template>
    <Loading v-if="loading" />
    <div v-else class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg"
                alt="Workflow" />
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Sign up</h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <Form class="space-y-6" @submit="onSubmit" :validation-schema="validationSchema">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700"> Name </label>
                        <div class="mt-1">
                            <Field id="name" name="name" type="text" autocomplete="name"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            <ErrorMessage class="text-sm text-red-600" name="name"></ErrorMessage>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700"> Email address </label>
                        <div class="mt-1">
                            <Field id="email" name="email" type="email" autocomplete="email"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            <ErrorMessage class="text-sm text-red-600" name="email"></ErrorMessage>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700"> Password </label>
                        <div class="mt-1">
                            <Field id="password" name="password" type="password" autocomplete="password"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            <ErrorMessage class="text-sm text-red-600" name="password"></ErrorMessage>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700"> Confirm Password
                        </label>
                        <div class="mt-1">
                            <Field id="password_confirmation" name="password_confirmation" type="password"
                                autocomplete="password_confirmation"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            <ErrorMessage class="text-sm text-red-600" name="password_confirmation"></ErrorMessage>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <RouterLink to="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
                                Or sign in to you account
                            </RouterLink>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Sign up
                        </button>
                    </div>

                    <div class="mt-4" v-if="errorMessage.length">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ errorMessage }}</span>
                        </div>
                    </div>
                </Form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300" />
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500"> Or continue with </span>
                        </div>
                    </div>

                    <NextcloudSignIn />
                </div>
            </div>
        </div>
    </div>
</template>
  