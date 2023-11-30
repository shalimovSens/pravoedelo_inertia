<script>
import Layout from "../Components/Layout"
import Container from "../Components/Container.vue"
import { Head, } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'


export default {
    components: {
        Layout,
        Container,
        Head,
    },
    data() {
        return {
            form: {
                email: null,
                password: null,
            },
        }
    },
    methods: {
        handleSubmit() {
            Inertia.post('/login', this.form)
            // router.post('/login', this.form)

                        // console.log(this.email, this.password)
            // await fetch('/login', {
            //     method: 'POST',
            //     data: JSON.stringify({
            //         email: this.form.email,
            //         password: this.form.password,
            //     }),
            //     headers: {
            //         'Content-Type': 'application/json',
            //     },
            // })
            //     .then(res => res.json())
            //     .catch(err => console.error(err))
            //     .then(data => console.log(data))
            //     .catch(err => console.error(err))
        }
    }
}
</script>
<template>
    <Head title="Авторизация"/>
    <Layout>
        <Container>
            <section class="auth mt-20 mx-auto max-w-md border-2 rounded lg:p-6 sm:p-4 p-2 shadow-sm">
                <h3 class="auth__title text-center lg:text-2xl lg:mb-6 sm:text-xl sm:mb-4 text-lg mb-3 font-medium">Авторизация</h3>
                <form
                    class="auth__form flex flex-col items-stretch lg:gap-y-8 sm:gap-y-7 gap-y-6"
                    @submit.prevent="handleSubmit"
                >
                    <input
                        v-model="form.email"
                        class="auth__input border-black"
                        type="text"
                        placeholder="Почта"
                        required
                    >
                    <input
                        v-model="form.password"
                        class="auth__input border-black"
                        type="password"
                        placeholder="Пароль"
                        required
                    >
                    <button class="auth__button h-14 bg-red-900 text-white rounded">Войти</button>
                </form>
            </section>
        </Container>
    </Layout>
</template>
