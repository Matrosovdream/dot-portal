<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Button from 'primevue/button';
import Card from 'primevue/card';

const router = useRouter();
const route = useRoute();
const auth = useAuthStore();

const form = ref({ email: '', password: '' });
const errors = ref({});
const submitting = ref(false);

async function submit() {
    errors.value = {};
    submitting.value = true;
    try {
        await auth.login(form.value);
        router.replace(route.query.next || '/dashboard');
    } catch (e) {
        errors.value = e.response?.data?.errors ?? { _: [e.response?.data?.message ?? 'Login failed'] };
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <div class="login-page">
        <Card class="login-card">
            <template #title>Sign in</template>
            <template #content>
                <form @submit.prevent="submit" class="form">
                    <div class="field">
                        <label>Email</label>
                        <InputText v-model="form.email" type="email" autocomplete="email" required />
                        <small v-if="errors.email" class="err">{{ errors.email[0] }}</small>
                    </div>
                    <div class="field">
                        <label>Password</label>
                        <Password v-model="form.password" :feedback="false" toggle-mask required />
                        <small v-if="errors.password" class="err">{{ errors.password[0] }}</small>
                    </div>
                    <small v-if="errors._" class="err">{{ errors._[0] }}</small>
                    <Button type="submit" label="Sign in" :loading="submitting" />
                    <div class="login-links">
                        <router-link :to="{ name: 'forgot-password' }">Forgot password?</router-link>
                        <router-link :to="{ name: 'register' }">Create account</router-link>
                    </div>
                </form>
            </template>
        </Card>
    </div>
</template>

<style scoped>
.login-page {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background: #f1f5f9;
}
.login-card { width: 380px; }
.form { display: flex; flex-direction: column; gap: 1rem; }
.field { display: flex; flex-direction: column; gap: 0.25rem; }
.err { color: #b00; font-size: 0.85rem; }
.login-links { display: flex; justify-content: space-between; gap: 1rem; font-size: 0.85rem; }
</style>
