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
import Tag from 'primevue/tag';
import { useAuthStore } from '@/stores/auth';
import { useProfileStore } from '@/stores/profile';
import { useReferencesStore } from '@/stores/references';
import { profileApi, unwrap, fieldErrors, errorMessage } from '@/api';

const toast = useToast();
const auth = useAuthStore();
const profileStore = useProfileStore();
const references = useReferencesStore();

const stateOptions = computed(() => references.options('states'));
const driverTypeOptions = computed(() => references.options('driver_types'));
const licenseTypeOptions = computed(() => references.options('license_types'));
const endorsementOptions = computed(() => references.options('license_endorsements'));
const isCompany = computed(() => auth.isCompany);
const isDriver = computed(() => auth.isDriver);

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

// ---- Driver file (driver role only) ----
const driverForm = reactive({
    employment: { dob: null, ssn: '', hire_date: null, driver_type_id: null },
    license: { type_id: null, endorsement_id: null, license_number: '', expiration_date: null, state_id: null },
    cdl: { license_number: '', expiration_date: null },
    medical: { examiner_name: '', national_registry: '', issue_date: null, expiration_date: null },
    drug_test: { test_type: '', test_date: null, result: '' },
    mvr: { mvr_number: '', mvr_date: null },
    address: { address1: '', address2: '', city: '', state_id: null, zip: '' },
});
const driverData = ref(null);
const driverSaving = ref(false);

const STATUS = {
    1: { label: 'Active', severity: 'success' },
    2: { label: 'Inactive', severity: 'warn' },
    3: { label: 'Terminated', severity: 'danger' },
};
const driverStatus = computed(() => STATUS[driverData.value?.status_id] ?? { label: '—', severity: 'secondary' });
const refLabel = (key, id) => (id ? references.label(key, id) : '—');
const fmtDate = (v) => (v ? new Date(v).toLocaleDateString() : '—');

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
    if (isDriver.value) loadDriver();
});

async function loadDriver() {
    try {
        populateDriver(await profileStore.loadDriver());
    } catch {
        /* driver file is optional */
    }
}

function populateDriver(d) {
    driverData.value = d;
    if (!d) return;
    const e = d.employment ?? {};
    Object.assign(driverForm.employment, {
        dob: toDate(e.dob), ssn: e.ssn ?? '', hire_date: toDate(e.hire_date), driver_type_id: e.driver_type_id ?? null,
    });
    if (d.license) Object.assign(driverForm.license, {
        type_id: d.license.type_id ?? null, endorsement_id: d.license.endorsement_id ?? null,
        license_number: d.license.license_number ?? '', expiration_date: toDate(d.license.expiration_date), state_id: d.license.state_id ?? null,
    });
    if (d.cdl) Object.assign(driverForm.cdl, { license_number: d.cdl.license_number ?? '', expiration_date: toDate(d.cdl.expiration_date) });
    if (d.medical) Object.assign(driverForm.medical, {
        examiner_name: d.medical.examiner_name ?? '', national_registry: d.medical.national_registry ?? '',
        issue_date: toDate(d.medical.issue_date), expiration_date: toDate(d.medical.expiration_date),
    });
    if (d.drug_test) Object.assign(driverForm.drug_test, { test_type: d.drug_test.test_type ?? '', test_date: toDate(d.drug_test.test_date), result: d.drug_test.result ?? '' });
    if (d.mvr) Object.assign(driverForm.mvr, { mvr_number: d.mvr.mvr_number ?? '', mvr_date: toDate(d.mvr.mvr_date) });
    if (d.address) Object.assign(driverForm.address, {
        address1: d.address.address1 ?? '', address2: d.address.address2 ?? '', city: d.address.city ?? '',
        state_id: d.address.state_id ?? null, zip: d.address.zip ?? '',
    });
}

async function saveDriver() {
    driverSaving.value = true;
    try {
        const f = driverForm;
        const payload = {
            employment: { ...f.employment, dob: toYmd(f.employment.dob), hire_date: toYmd(f.employment.hire_date) },
            license: { ...f.license, expiration_date: toYmd(f.license.expiration_date) },
            cdl: { ...f.cdl, expiration_date: toYmd(f.cdl.expiration_date) },
            medical: { ...f.medical, issue_date: toYmd(f.medical.issue_date), expiration_date: toYmd(f.medical.expiration_date) },
            drug_test: { ...f.drug_test, test_date: toYmd(f.drug_test.test_date) },
            mvr: { ...f.mvr, mvr_date: toYmd(f.mvr.mvr_date) },
            address: { ...f.address },
        };
        populateDriver(await profileStore.saveDriver(payload));
        toast.add({ severity: 'success', summary: 'Saved', detail: 'Driver profile updated.', life: 3000 });
    } catch (e) {
        if (e.response?.status === 422) {
            const detail = Object.values(fieldErrors(e)).flat().slice(0, 3).join(' ');
            toast.add({ severity: 'error', summary: 'Check the form', detail: detail || 'Validation failed.', life: 5000 });
        } else {
            toast.add({ severity: 'error', summary: 'Failed', detail: errorMessage(e), life: 4000 });
        }
    } finally {
        driverSaving.value = false;
    }
}

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
    <div class="profile-page">
        <!-- Preview summary card (driver) -->
        <div v-if="isDriver" class="surface-card preview-card">
            <div class="preview-head">
                <div class="preview-avatar"><i class="pi pi-user" /></div>
                <div class="preview-id">
                    <div class="preview-name">{{ auth.me?.fullname || auth.me?.firstname || auth.me?.email }}</div>
                    <div class="preview-sub">
                        {{ auth.me?.email }}<span v-if="auth.me?.phone"> · {{ auth.me.phone }}</span>
                    </div>
                </div>
                <Tag class="preview-status" :severity="driverStatus.severity" :value="driverStatus.label" />
            </div>
            <div class="preview-grid">
                <div><span class="preview-label">Driver type</span><span class="preview-val">{{ refLabel('driver_types', driverForm.employment.driver_type_id) }}</span></div>
                <div><span class="preview-label">Hire date</span><span class="preview-val">{{ fmtDate(driverForm.employment.hire_date) }}</span></div>
                <div><span class="preview-label">License #</span><span class="preview-val">{{ driverForm.license.license_number || '—' }}</span></div>
                <div><span class="preview-label">License exp.</span><span class="preview-val">{{ fmtDate(driverForm.license.expiration_date) }}</span></div>
                <div><span class="preview-label">CDL #</span><span class="preview-val">{{ driverForm.cdl.license_number || '—' }}</span></div>
                <div><span class="preview-label">Medical exp.</span><span class="preview-val">{{ fmtDate(driverForm.medical.expiration_date) }}</span></div>
            </div>
        </div>

        <div class="surface-card profile-wrap">
        <Tabs value="profile">
            <TabList>
                <Tab value="profile">Profile</Tab>
                <Tab v-if="!isDriver" value="address">Address</Tab>
                <Tab v-if="isCompany" value="company">Company</Tab>
                <template v-if="isDriver">
                    <Tab value="employment">Employment</Tab>
                    <Tab value="license">License</Tab>
                    <Tab value="medical">Medical</Tab>
                    <Tab value="records">Records</Tab>
                    <Tab value="driver_address">Address</Tab>
                </template>
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

                <!-- Address (non-driver: user address) -->
                <TabPanel v-if="!isDriver" value="address">
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

                <!-- Driver: Employment -->
                <TabPanel v-if="isDriver" value="employment">
                    <form class="form-grid" @submit.prevent="saveDriver">
                        <div class="form-field">
                            <label>Driver type</label>
                            <Select v-model="driverForm.employment.driver_type_id" :options="driverTypeOptions" optionLabel="label" optionValue="value" showClear placeholder="Select" />
                        </div>
                        <div class="form-field">
                            <label>SSN</label>
                            <InputText v-model="driverForm.employment.ssn" />
                        </div>
                        <div class="form-field">
                            <label>Date of birth</label>
                            <DatePicker v-model="driverForm.employment.dob" dateFormat="yy-mm-dd" showIcon />
                        </div>
                        <div class="form-field">
                            <label>Hire date</label>
                            <DatePicker v-model="driverForm.employment.hire_date" dateFormat="yy-mm-dd" showIcon />
                        </div>
                        <div class="form-field col-span-2 form-actions">
                            <Button type="submit" label="Save" icon="pi pi-check" :loading="driverSaving" />
                        </div>
                    </form>
                </TabPanel>

                <!-- Driver: License + CDL -->
                <TabPanel v-if="isDriver" value="license">
                    <form class="form-grid" @submit.prevent="saveDriver">
                        <div class="form-field">
                            <label>License type</label>
                            <Select v-model="driverForm.license.type_id" :options="licenseTypeOptions" optionLabel="label" optionValue="value" showClear placeholder="Select" />
                        </div>
                        <div class="form-field">
                            <label>Endorsement</label>
                            <Select v-model="driverForm.license.endorsement_id" :options="endorsementOptions" optionLabel="label" optionValue="value" showClear placeholder="Select" />
                        </div>
                        <div class="form-field">
                            <label>License number</label>
                            <InputText v-model="driverForm.license.license_number" />
                        </div>
                        <div class="form-field">
                            <label>Expiration date</label>
                            <DatePicker v-model="driverForm.license.expiration_date" dateFormat="yy-mm-dd" showIcon />
                        </div>
                        <div class="form-field">
                            <label>State</label>
                            <Select v-model="driverForm.license.state_id" :options="stateOptions" optionLabel="label" optionValue="value" filter showClear placeholder="Select state" />
                        </div>

                        <div class="form-field col-span-2"><h4 class="sub-h">CDL</h4></div>
                        <div class="form-field">
                            <label>CDL number</label>
                            <InputText v-model="driverForm.cdl.license_number" />
                        </div>
                        <div class="form-field">
                            <label>CDL expiration date</label>
                            <DatePicker v-model="driverForm.cdl.expiration_date" dateFormat="yy-mm-dd" showIcon />
                        </div>
                        <div v-if="driverData?.cdl?.file" class="form-field col-span-2">
                            <a class="file-link" :href="driverData.cdl.file.download_url" target="_blank" rel="noopener">
                                <i class="pi pi-file" /> {{ driverData.cdl.file.filename }}
                            </a>
                        </div>

                        <div class="form-field col-span-2 form-actions">
                            <Button type="submit" label="Save" icon="pi pi-check" :loading="driverSaving" />
                        </div>
                    </form>
                </TabPanel>

                <!-- Driver: Medical card -->
                <TabPanel v-if="isDriver" value="medical">
                    <form class="form-grid" @submit.prevent="saveDriver">
                        <div class="form-field">
                            <label>Examiner name</label>
                            <InputText v-model="driverForm.medical.examiner_name" />
                        </div>
                        <div class="form-field">
                            <label>National registry #</label>
                            <InputText v-model="driverForm.medical.national_registry" />
                        </div>
                        <div class="form-field">
                            <label>Issue date</label>
                            <DatePicker v-model="driverForm.medical.issue_date" dateFormat="yy-mm-dd" showIcon />
                        </div>
                        <div class="form-field">
                            <label>Expiration date</label>
                            <DatePicker v-model="driverForm.medical.expiration_date" dateFormat="yy-mm-dd" showIcon />
                        </div>
                        <div class="form-field col-span-2 form-actions">
                            <Button type="submit" label="Save" icon="pi pi-check" :loading="driverSaving" />
                        </div>
                    </form>
                </TabPanel>

                <!-- Driver: Records (drug test + MVR) -->
                <TabPanel v-if="isDriver" value="records">
                    <form class="form-grid" @submit.prevent="saveDriver">
                        <div class="form-field col-span-2"><h4 class="sub-h">Drug test</h4></div>
                        <div class="form-field">
                            <label>Test type</label>
                            <InputText v-model="driverForm.drug_test.test_type" />
                        </div>
                        <div class="form-field">
                            <label>Test date</label>
                            <DatePicker v-model="driverForm.drug_test.test_date" dateFormat="yy-mm-dd" showIcon />
                        </div>
                        <div class="form-field">
                            <label>Result</label>
                            <InputText v-model="driverForm.drug_test.result" />
                        </div>
                        <div v-if="driverData?.drug_test?.file" class="form-field col-span-2">
                            <a class="file-link" :href="driverData.drug_test.file.download_url" target="_blank" rel="noopener">
                                <i class="pi pi-file" /> {{ driverData.drug_test.file.filename }}
                            </a>
                        </div>

                        <div class="form-field col-span-2"><h4 class="sub-h">MVR</h4></div>
                        <div class="form-field">
                            <label>MVR number</label>
                            <InputText v-model="driverForm.mvr.mvr_number" />
                        </div>
                        <div class="form-field">
                            <label>MVR date</label>
                            <DatePicker v-model="driverForm.mvr.mvr_date" dateFormat="yy-mm-dd" showIcon />
                        </div>
                        <div v-if="driverData?.mvr?.file" class="form-field col-span-2">
                            <a class="file-link" :href="driverData.mvr.file.download_url" target="_blank" rel="noopener">
                                <i class="pi pi-file" /> {{ driverData.mvr.file.filename }}
                            </a>
                        </div>

                        <div class="form-field col-span-2 form-hint">
                            <i class="pi pi-info-circle" /> Uploading new scans from your profile is coming soon — existing files are shown above.
                        </div>
                        <div class="form-field col-span-2 form-actions">
                            <Button type="submit" label="Save" icon="pi pi-check" :loading="driverSaving" />
                        </div>
                    </form>
                </TabPanel>

                <!-- Driver: Address -->
                <TabPanel v-if="isDriver" value="driver_address">
                    <form class="form-grid" @submit.prevent="saveDriver">
                        <div class="form-field col-span-2">
                            <label>Address line 1</label>
                            <InputText v-model="driverForm.address.address1" />
                        </div>
                        <div class="form-field col-span-2">
                            <label>Address line 2</label>
                            <InputText v-model="driverForm.address.address2" />
                        </div>
                        <div class="form-field">
                            <label>City</label>
                            <InputText v-model="driverForm.address.city" />
                        </div>
                        <div class="form-field">
                            <label>State</label>
                            <Select v-model="driverForm.address.state_id" :options="stateOptions" optionLabel="label" optionValue="value" filter showClear placeholder="Select state" />
                        </div>
                        <div class="form-field">
                            <label>ZIP</label>
                            <InputText v-model="driverForm.address.zip" />
                        </div>
                        <div class="form-field col-span-2 form-actions">
                            <Button type="submit" label="Save" icon="pi pi-check" :loading="driverSaving" />
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
    </div>
</template>

<style scoped>
.profile-page { max-width: 900px; }
.profile-wrap { padding: 0.5rem 1rem 1rem; }
.sub-h { margin: 0.5rem 0 0; font-size: 0.95rem; font-weight: 700; color: var(--p-text-color); }
.form-actions { justify-content: flex-end; }

/* Driver preview card */
.preview-card { padding: 1.25rem 1.5rem; margin-bottom: 1.25rem; }
.preview-head { display: flex; align-items: center; gap: 1rem; }
.preview-avatar {
    width: 3rem; height: 3rem; border-radius: 50%;
    display: grid; place-items: center; font-size: 1.3rem;
    background: var(--p-highlight-background); color: var(--p-primary-color); flex: 0 0 auto;
}
.preview-id { min-width: 0; }
.preview-name { font-size: 1.15rem; font-weight: 700; line-height: 1.2; }
.preview-sub { font-size: 0.85rem; color: var(--p-text-muted-color); }
.preview-status { margin-left: auto; }
.preview-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 0.75rem 1.5rem; margin-top: 1.25rem;
    border-top: 1px solid var(--p-content-border-color); padding-top: 1rem;
}
.preview-grid > div { display: flex; flex-direction: column; gap: 0.15rem; }
.preview-label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.03em; color: var(--p-text-muted-color); }
.preview-val { font-weight: 600; }
.form-hint { font-size: 0.8rem; color: var(--p-text-muted-color); display: flex; align-items: center; gap: 0.4rem; }
.file-link { display: inline-flex; align-items: center; gap: 0.4rem; color: var(--p-primary-color); text-decoration: none; font-weight: 500; }
.file-link:hover { text-decoration: underline; }
</style>
