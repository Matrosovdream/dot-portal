<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Select from 'primevue/select';
import Button from 'primevue/button';
import { useAuthStore } from '@/stores/auth';
import { authApi, unwrap, fieldErrors, errorMessage } from '@/api';

const router = useRouter();
const auth = useAuthStore();

const form = reactive({
    firstname: '', lastname: '', email: '', phone: '',
    password: '', password_confirmation: '', role: 'company',
});
const roleOptions = [
    { label: 'Company', value: 'company' },
    { label: 'Driver', value: 'driver' },
];
const errors = ref({});
const submitting = ref(false);

async function submit() {
    errors.value = {};
    submitting.value = true;
    try {
        const data = unwrap(await authApi.register({ ...form }));
        auth.setMe(data);
        router.replace({ name: 'dashboard' });
    } catch (e) {
        if (e.response?.status === 422) errors.value = fieldErrors(e);
        else errors.value = { _: [errorMessage(e, 'Registration failed')] };
    } finally {
        submitting.value = false;
    }
}

const err = (f) => errors.value[f]?.[0];
</script>

<template>
    <div class="auth-page">
        <Card class="auth-card">
            <template #title>
                <div class="auth-head">
                    <div class="auth-brand">DOT Portal</div>
                    <div class="auth-sub">Create your account</div>
                </div>
            </template>
            <template #content>
                <form class="auth-form" @submit.prevent="submit">
                    <div class="field">
                        <label>First name</label>
                        <InputText v-model="form.firstname" :invalid="!!err('firstname')" autocomplete="given-name" />
                        <small v-if="err('firstname')" class="err">{{ err('firstname') }}</small>
                    </div>
                    <div class="field">
                        <label>Last name</label>
                        <InputText v-model="form.lastname" :invalid="!!err('lastname')" autocomplete="family-name" />
                        <small v-if="err('lastname')" class="err">{{ err('lastname') }}</small>
                    </div>
                    <div class="field">
                        <label>Email</label>
                        <InputText v-model="form.email" type="email" :invalid="!!err('email')" autocomplete="email" />
                        <small v-if="err('email')" class="err">{{ err('email') }}</small>
                    </div>
                    <div class="field">
                        <label>Phone (optional)</label>
                        <InputText v-model="form.phone" :invalid="!!err('phone')" autocomplete="tel" />
                        <small v-if="err('phone')" class="err">{{ err('phone') }}</small>
                    </div>
                    <div class="field">
                        <label>Account type</label>
                        <Select v-model="form.role" :options="roleOptions" optionLabel="label" optionValue="value" />
                    </div>
                    <div class="field">
                        <label>Password</label>
                        <Password v-model="form.password" toggleMask :invalid="!!err('password')" :inputProps="{ autocomplete: 'new-password' }" />
                        <small v-if="err('password')" class="err">{{ err('password') }}</small>
                    </div>
                    <div class="field">
                        <label>Confirm password</label>
                        <Password v-model="form.password_confirmation" :feedback="false" toggleMask :inputProps="{ autocomplete: 'new-password' }" />
                    </div>
                    <small v-if="err('_')" class="err">{{ err('_') }}</small>
                    <Button type="submit" label="Create account" :loading="submitting" />
                    <div class="auth-links">
                        <router-link :to="{ name: 'login' }">Already have an account? Sign in</router-link>
                    </div>
                </form>
            </template>
        </Card>
    </div>
</template>
