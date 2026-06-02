<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Password from 'primevue/password';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import Button from 'primevue/button';
import { useAuthStore } from '@/stores/auth';
import { useProfileStore } from '@/stores/profile';
import { useReferencesStore } from '@/stores/references';
import { profileApi, unwrap, fieldErrors, errorMessage } from '@/api';

const toast = useToast();
const auth = useAuthStore();
const profileStore = useProfileStore();
const references = useReferencesStore();

const stateOptions = computed(() => references.options('states'));
const isCompany = computed(() => auth.isCompany);

function toDate(v) { return v ? new Date(`${v}T00:00:00`) : null; }
function toYmd(v) {
    if (!v) return null;
    if (typeof v === 'string') return v;
    const d = v;
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
}
const err = (bag, f) => bag.value[f]?.[0];

// ---- Profile ----
const profileForm = reactive({ firstname: '', lastname: '', phone: '', email: '', birthday: null });
const profileErrors = ref({});
const profileSaving = ref(false);

// ---- Address ----
const addressForm = reactive({ address1: '', address2: '', city: '', state_id: null, zip: '' });
const addressErrors = ref({});
const addressSaving = ref(false);

// ---- Company ----
const companyForm = reactive({
    name: '', phone: '', dot_number: '', mc_number: '', trucks_number: null, drivers_number: null,
    business: { address1: '', address2: '', city: '', state_id: null, zip: '' },
    mailing: { address1: '', address2: '', city: '', state_id: null, zip: '' },
});
const companyErrors = ref({});
const companySaving = ref(false);

// ---- Password ----
const passwordForm = reactive({ current_password: '', password: '', password_confirmation: '' });
const passwordErrors = ref({});
const passwordSaving = ref(false);

onMounted(async () => {
    references.load();
    const p = await profileStore.load(true);
    if (p) {
        Object.assign(profileForm, {
            firstname: p.firstname ?? '', lastname: p.lastname ?? '', phone: p.phone ?? '',
            email: p.email ?? '', birthday: toDate(p.birthday),
        });
        if (p.address) Object.assign(addressForm, { ...p.address });
    }
    if (isCompany.value) loadCompany();
});

async function loadCompany() {
    try {
        const c = unwrap(await profileApi.getCompany());
        if (!c) return;
        Object.assign(companyForm, {
            name: c.name ?? '', phone: c.phone ?? '', dot_number: c.dot_number ?? '',
            mc_number: c.mc_number ?? '', trucks_number: c.trucks_number ?? null, drivers_number: c.drivers_number ?? null,
        });
        if (c.addresses?.business) Object.assign(companyForm.business, c.addresses.business);
        if (c.addresses?.mailing) Object.assign(companyForm.mailing, c.addresses.mailing);
    } catch {
        /* company is optional */
    }
}

async function saveProfile() {
    profileErrors.value = {};
    profileSaving.value = true;
    try {
        await profileStore.save({
            firstname: profileForm.firstname, lastname: profileForm.lastname,
            phone: profileForm.phone, email: profileForm.email, birthday: toYmd(profileForm.birthday),
        });
        await auth.bootstrap();
        toast.add({ severity: 'success', summary: 'Saved', detail: 'Profile updated.', life: 3000 });
    } catch (e) {
        handle(e, profileErrors);
    } finally {
        profileSaving.value = false;
    }
}

async function saveAddress() {
    addressErrors.value = {};
    addressSaving.value = true;
    try {
        await profileStore.saveAddress({ ...addressForm });
        toast.add({ severity: 'success', summary: 'Saved', detail: 'Address updated.', life: 3000 });
    } catch (e) {
        handle(e, addressErrors);
    } finally {
        addressSaving.value = false;
    }
}

async function saveCompany() {
    companyErrors.value = {};
    companySaving.value = true;
    try {
        await profileStore.saveCompany({ ...companyForm });
        toast.add({ severity: 'success', summary: 'Saved', detail: 'Company updated.', life: 3000 });
    } catch (e) {
        handle(e, companyErrors);
    } finally {
        companySaving.value = false;
    }
}

async function changePassword() {
    passwordErrors.value = {};
    passwordSaving.value = true;
    try {
        await profileStore.changePassword({ ...passwordForm });
        Object.assign(passwordForm, { current_password: '', password: '', password_confirmation: '' });
        toast.add({ severity: 'success', summary: 'Saved', detail: 'Password changed.', life: 3000 });
    } catch (e) {
        handle(e, passwordErrors);
    } finally {
        passwordSaving.value = false;
    }
}

function handle(e, bag) {
    if (e.response?.status === 422) bag.value = fieldErrors(e);
    else toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
}
</script>

<template>
    <div class="surface-card profile-wrap">
        <Tabs value="profile">
            <TabList>
                <Tab value="profile">Profile</Tab>
                <Tab value="address">Address</Tab>
                <Tab v-if="isCompany" value="company">Company</Tab>
                <Tab value="password">Password</Tab>
            </TabList>
            <TabPanels>
                <!-- Profile -->
                <TabPanel value="profile">
                    <form class="form-grid" @submit.prevent="saveProfile">
                        <div class="form-field">
                            <label>First name</label>
                            <InputText v-model="profileForm.firstname" :invalid="!!err(profileErrors, 'firstname')" />
                            <small v-if="err(profileErrors, 'firstname')" class="field-error">{{ err(profileErrors, 'firstname') }}</small>
                        </div>
                        <div class="form-field">
                            <label>Last name</label>
                            <InputText v-model="profileForm.lastname" :invalid="!!err(profileErrors, 'lastname')" />
                            <small v-if="err(profileErrors, 'lastname')" class="field-error">{{ err(profileErrors, 'lastname') }}</small>
                        </div>
                        <div class="form-field">
                            <label>Email</label>
                            <InputText v-model="profileForm.email" type="email" :invalid="!!err(profileErrors, 'email')" />
                            <small v-if="err(profileErrors, 'email')" class="field-error">{{ err(profileErrors, 'email') }}</small>
                        </div>
                        <div class="form-field">
                            <label>Phone</label>
                            <InputText v-model="profileForm.phone" />
                        </div>
                        <div class="form-field">
                            <label>Birthday</label>
                            <DatePicker v-model="profileForm.birthday" dateFormat="yy-mm-dd" showIcon />
                        </div>
                        <div class="form-field col-span-2 form-actions">
                            <Button type="submit" label="Save profile" icon="pi pi-check" :loading="profileSaving" />
                        </div>
                    </form>
                </TabPanel>

                <!-- Address -->
                <TabPanel value="address">
                    <form class="form-grid" @submit.prevent="saveAddress">
                        <div class="form-field col-span-2">
                            <label>Address line 1</label>
                            <InputText v-model="addressForm.address1" />
                        </div>
                        <div class="form-field col-span-2">
                            <label>Address line 2</label>
                            <InputText v-model="addressForm.address2" />
                        </div>
                        <div class="form-field">
                            <label>City</label>
                            <InputText v-model="addressForm.city" />
                        </div>
                        <div class="form-field">
                            <label>State</label>
                            <Select v-model="addressForm.state_id" :options="stateOptions" optionLabel="label" optionValue="value" filter showClear placeholder="Select state" />
                        </div>
                        <div class="form-field">
                            <label>ZIP *</label>
                            <InputText v-model="addressForm.zip" :invalid="!!err(addressErrors, 'zip')" />
                            <small v-if="err(addressErrors, 'zip')" class="field-error">{{ err(addressErrors, 'zip') }}</small>
                        </div>
                        <div class="form-field col-span-2 form-actions">
                            <Button type="submit" label="Save address" icon="pi pi-check" :loading="addressSaving" />
                        </div>
                    </form>
                </TabPanel>

                <!-- Company -->
                <TabPanel v-if="isCompany" value="company">
                    <form class="form-grid" @submit.prevent="saveCompany">
                        <div class="form-field">
                            <label>Company name</label>
                            <InputText v-model="companyForm.name" />
                        </div>
                        <div class="form-field">
                            <label>Phone</label>
                            <InputText v-model="companyForm.phone" />
                        </div>
                        <div class="form-field">
                            <label>DOT number</label>
                            <InputText v-model="companyForm.dot_number" />
                        </div>
                        <div class="form-field">
                            <label>MC number</label>
                            <InputText v-model="companyForm.mc_number" />
                        </div>
                        <div class="form-field">
                            <label>Trucks</label>
                            <InputNumber v-model="companyForm.trucks_number" :min="0" showButtons />
                        </div>
                        <div class="form-field">
                            <label>Drivers</label>
                            <InputNumber v-model="companyForm.drivers_number" :min="0" showButtons />
                        </div>

                        <div class="form-field col-span-2"><h4 class="sub-h">Business address</h4></div>
                        <div class="form-field col-span-2">
                            <label>Address line 1</label>
                            <InputText v-model="companyForm.business.address1" />
                        </div>
                        <div class="form-field">
                            <label>City</label>
                            <InputText v-model="companyForm.business.city" />
                        </div>
                        <div class="form-field">
                            <label>State</label>
                            <Select v-model="companyForm.business.state_id" :options="stateOptions" optionLabel="label" optionValue="value" filter showClear placeholder="Select state" />
                        </div>
                        <div class="form-field">
                            <label>ZIP</label>
                            <InputText v-model="companyForm.business.zip" />
                        </div>

                        <div class="form-field col-span-2"><h4 class="sub-h">Mailing address</h4></div>
                        <div class="form-field col-span-2">
                            <label>Address line 1</label>
                            <InputText v-model="companyForm.mailing.address1" />
                        </div>
                        <div class="form-field">
                            <label>City</label>
                            <InputText v-model="companyForm.mailing.city" />
                        </div>
                        <div class="form-field">
                            <label>State</label>
                            <Select v-model="companyForm.mailing.state_id" :options="stateOptions" optionLabel="label" optionValue="value" filter showClear placeholder="Select state" />
                        </div>
                        <div class="form-field">
                            <label>ZIP</label>
                            <InputText v-model="companyForm.mailing.zip" />
                        </div>

                        <div class="form-field col-span-2 form-actions">
                            <Button type="submit" label="Save company" icon="pi pi-check" :loading="companySaving" />
                        </div>
                    </form>
                </TabPanel>

                <!-- Password -->
                <TabPanel value="password">
                    <form class="form-grid" @submit.prevent="changePassword">
                        <div class="form-field col-span-2">
                            <label>Current password</label>
                            <Password v-model="passwordForm.current_password" :feedback="false" toggleMask :invalid="!!err(passwordErrors, 'current_password')" />
                            <small v-if="err(passwordErrors, 'current_password')" class="field-error">{{ err(passwordErrors, 'current_password') }}</small>
                        </div>
                        <div class="form-field">
                            <label>New password</label>
                            <Password v-model="passwordForm.password" toggleMask :invalid="!!err(passwordErrors, 'password')" :inputProps="{ autocomplete: 'new-password' }" />
                            <small v-if="err(passwordErrors, 'password')" class="field-error">{{ err(passwordErrors, 'password') }}</small>
                        </div>
                        <div class="form-field">
                            <label>Confirm new password</label>
                            <Password v-model="passwordForm.password_confirmation" :feedback="false" toggleMask :inputProps="{ autocomplete: 'new-password' }" />
                        </div>
                        <div class="form-field col-span-2 form-actions">
                            <Button type="submit" label="Change password" icon="pi pi-key" :loading="passwordSaving" />
                        </div>
                    </form>
                </TabPanel>
            </TabPanels>
        </Tabs>
    </div>
</template>

<style scoped>
.profile-wrap { max-width: 900px; padding: 0.5rem 1rem 1rem; }
.sub-h { margin: 0.5rem 0 0; font-size: 0.95rem; font-weight: 700; color: var(--p-text-color); }
.form-actions { justify-content: flex-end; }
</style>
