<script setup>
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import { authApi, fieldErrors, errorMessage } from '@/api';

const props = defineProps({ token: { type: String, default: '' } });
const route = useRoute();
const router = useRouter();
const toast = useToast();

const form = reactive({
    token: props.token || route.params.token || '',
    email: route.query.email || '',
    password: '',
    password_confirmation: '',
});
const errors = ref({});
const submitting = ref(false);

async function submit() {
    errors.value = {};
    submitting.value = true;
    try {
        await authApi.passwordReset({ ...form });
        toast.add({ severity: 'success', summary: 'Password reset', detail: 'You can now sign in.', life: 4000 });
        router.replace({ name: 'login' });
    } catch (e) {
        if (e.response?.status === 422) errors.value = fieldErrors(e);
        else errors.value = { _: [errorMessage(e, 'Could not reset password')] };
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
                    <div class="auth-sub">Choose a new password</div>
                </div>
            </template>
            <template #content>
                <form class="auth-form" @submit.prevent="submit">
                    <div class="field">
                        <label>Email</label>
                        <InputText v-model="form.email" type="email" :invalid="!!err('email')" autocomplete="email" />
                        <small v-if="err('email')" class="err">{{ err('email') }}</small>
                    </div>
                    <div class="field">
                        <label>New password</label>
                        <Password v-model="form.password" toggleMask :invalid="!!err('password')" :inputProps="{ autocomplete: 'new-password' }" />
                        <small v-if="err('password')" class="err">{{ err('password') }}</small>
                    </div>
                    <div class="field">
                        <label>Confirm new password</label>
                        <Password v-model="form.password_confirmation" :feedback="false" toggleMask :inputProps="{ autocomplete: 'new-password' }" />
                    </div>
                    <small v-if="err('_')" class="err">{{ err('_') }}</small>
                    <Button type="submit" label="Reset password" :loading="submitting" />
                    <div class="auth-links">
                        <router-link :to="{ name: 'login' }">Back to sign in</router-link>
                    </div>
                </form>
            </template>
        </Card>
    </div>
</template>
