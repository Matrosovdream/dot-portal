import { api } from './client';

export const referencesApi = {
    bundle: () => api.get('/references/bundle').then((r) => r.data),
    states: () => api.get('/references/states').then((r) => r.data),
    driverTypes: () => api.get('/references/driver-types').then((r) => r.data),
    licenseTypes: () => api.get('/references/license-types').then((r) => r.data),
    licenseEndorsements: () => api.get('/references/license-endorsements').then((r) => r.data),
    vehicleOwnershipTypes: () => api.get('/references/vehicle-ownership-types').then((r) => r.data),
    vehicleUnitTypes: () => api.get('/references/vehicle-unit-types').then((r) => r.data),
    queryPrices: () => api.get('/references/query-prices').then((r) => r.data),
    paymentMethods: () => api.get('/references/payment-methods').then((r) => r.data),
    requestStatuses: () => api.get('/references/request-statuses').then((r) => r.data),
    orderStatuses: () => api.get('/references/order-statuses').then((r) => r.data),
    serviceGroups: () => api.get('/references/service-groups').then((r) => r.data),
};
