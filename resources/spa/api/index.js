// Single import surface for the whole API layer.
//   import { driversApi, errorMessage } from '@/api';
export { api, bootstrapCsrf, unwrap, fieldErrors, errorMessage } from './client';
export { crud } from './resource';

export { authApi } from './auth';
export { profileApi } from './profile';
export { referencesApi } from './references';
export { globalsApi } from './globals';
export { dashboardApi } from './dashboard';
export { driversApi } from './drivers';
export { vehiclesApi } from './vehicles';
export { insuranceVehiclesApi } from './insuranceVehicles';
export { serviceRequestsApi } from './serviceRequests';
export { subscriptionApi } from './subscription';
export { ordersApi } from './orders';
export { todoApi } from './todo';
export { notificationsApi } from './notifications';
export { searchApi } from './search';
export { saferwebApi } from './saferweb';
export { documentsApi } from './documents';
export { filesApi } from './files';

// Admin
export { adminServicesApi } from './admin/services';
export { adminServiceFieldsApi } from './admin/serviceFields';
export { adminServiceGroupsApi } from './admin/serviceGroups';
export { adminRequestsApi } from './admin/requests';
export { adminSubPlansApi } from './admin/subPlans';
export { adminSubRequestsApi } from './admin/subRequests';
export { adminSubManagerApi } from './admin/subManager';
export { adminPlanFeesApi } from './admin/planFees';
export { adminGatewaysApi } from './admin/gateways';
export { adminSettingsApi } from './admin/settings';
export { adminUsersApi } from './admin/users';
export { adminNotificationsApi } from './admin/notificationsManage';
