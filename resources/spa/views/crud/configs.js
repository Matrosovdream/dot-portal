// Config-driven CRUD sections. Each entry powers CrudListView + CrudFormView
// (looked up by route.meta.section). Column `ref` resolves an id → label via
// the references bundle. Form `options` is "references:<key>", a static csv
// "Label:value,..", or omitted (plain input). `api` is the section's api module.
import { insuranceVehiclesApi } from '@/api/insuranceVehicles';
import { documentsApi } from '@/api/documents';
import { adminServicesApi } from '@/api/admin/services';
import { adminServiceFieldsApi } from '@/api/admin/serviceFields';
import { adminServiceGroupsApi } from '@/api/admin/serviceGroups';
import { adminSubPlansApi } from '@/api/admin/subPlans';
import { adminSubRequestsApi } from '@/api/admin/subRequests';
import { adminSubManagerApi } from '@/api/admin/subManager';
import { adminPlanFeesApi } from '@/api/admin/planFees';
import { adminGatewaysApi } from '@/api/admin/gateways';
import { adminUsersApi } from '@/api/admin/users';
import { adminNotificationsApi } from '@/api/admin/notificationsManage';
import { adminCompaniesApi } from '@/api/admin/companies';

const ROLE_OPTIONS = 'Admin:admin,Manager:manager,Company:company,Driver:driver';
const SUB_STATUS = 'Active:active,Cancelled:cancelled,Pending:pending';

export const sections = {
    'insurance-vehicles': {
        title: 'Insurance Vehicles',
        singular: 'Policy',
        icon: 'pi pi-shield',
        routeBase: '/insurance-vehicles',
        api: insuranceVehiclesApi,
        canCreate: true, canEdit: true, canDelete: true,
        columns: [
            { key: 'name', label: 'Name', type: 'text' },
            { key: 'number', label: 'Policy #', type: 'text' },
            { key: 'start_date', label: 'Start', type: 'date' },
            { key: 'end_date', label: 'End', type: 'date' },
            { key: 'created_at', label: 'Created', type: 'datetime' },
            { key: 'owner', label: 'User', type: 'owner', adminOnly: true },
        ],
        filters: [{ key: 'user_id', label: 'Filter by user', type: 'user', adminOnly: true }],
        formFields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            { name: 'number', label: 'Policy Number', type: 'text' },
            { name: 'start_date', label: 'Start Date', type: 'date' },
            { name: 'end_date', label: 'End Date', type: 'date' },
            { name: 'file_id', label: 'Attached File ID', type: 'number' },
        ],
    },

    documents: {
        title: 'Documents',
        icon: 'pi pi-folder',
        routeBase: '/documents',
        api: documentsApi,
        canCreate: false, canEdit: false, canDelete: false,
        rowOpenField: 'url',
        columns: [
            { key: 'name', label: 'Filename', type: 'text' },
            { key: 'type', label: 'Type', type: 'text' },
            { key: 'extension', label: 'Ext', type: 'text' },
            { key: 'size_bytes', label: 'Size (bytes)', type: 'number' },
            { key: 'created_at', label: 'Created', type: 'datetime' },
            { key: 'owner', label: 'User', type: 'owner', adminOnly: true },
        ],
        filters: [
            { key: 'q', label: 'Search filename', type: 'text' },
            { key: 'user_id', label: 'Filter by user', type: 'user', adminOnly: true },
        ],
        formFields: [],
    },

    'admin.companies': {
        title: 'Companies',
        singular: 'Company',
        icon: 'pi pi-building',
        routeBase: '/admin/companies',
        api: adminCompaniesApi,
        canCreate: true, canEdit: true, canDelete: true,
        columns: [
            { key: 'name', label: 'Company', type: 'text' },
            { key: 'dot_number', label: 'DOT #', type: 'text' },
            { key: 'mc_number', label: 'MC #', type: 'text' },
            { key: 'phone', label: 'Phone', type: 'text' },
            { key: 'trucks_number', label: 'Trucks', type: 'number' },
            { key: 'drivers_number', label: 'Drivers', type: 'number' },
            { key: 'owner', label: 'Owner', type: 'owner' },
            { key: 'is_active', label: 'Active', type: 'boolean' },
            { key: 'created_at', label: 'Created', type: 'datetime' },
        ],
        filters: [{ key: 'q', label: 'Search name / DOT / MC', type: 'text' }],
        formFields: [
            // user_id (owner account) is set on create only; the update endpoint ignores it.
            { name: 'user_id', label: 'Owner User ID', type: 'number', required: true, createOnly: true },
            { name: 'name', label: 'Company name', type: 'text', required: true },
            { name: 'phone', label: 'Phone', type: 'text' },
            { name: 'dot_number', label: 'DOT #', type: 'text' },
            { name: 'mc_number', label: 'MC #', type: 'text' },
            { name: 'trucks_number', label: 'Trucks', type: 'number' },
            { name: 'drivers_number', label: 'Drivers', type: 'number' },
        ],
    },

    'admin.gateways': {
        title: 'Payment Gateways',
        icon: 'pi pi-credit-card',
        routeBase: '/admin/gateways',
        api: adminGatewaysApi,
        canCreate: false, canEdit: false, canDelete: false,
        columns: [
            { key: 'name', label: 'Name', type: 'text' },
            { key: 'slug', label: 'Slug', type: 'text' },
            { key: 'description', label: 'Description', type: 'text' },
            { key: 'is_active', label: 'Active', type: 'boolean' },
            { key: 'updated_at', label: 'Updated', type: 'datetime' },
        ],
        filters: [{ key: 'q', label: 'Search by name', type: 'text' }],
        formFields: [],
    },

    'admin.services': {
        title: 'Services',
        singular: 'Service',
        icon: 'pi pi-cog',
        routeBase: '/admin/services',
        api: adminServicesApi,
        canCreate: true, canEdit: true, canDelete: true,
        columns: [
            { key: 'name', label: 'Name', type: 'text' },
            { key: 'slug', label: 'Slug', type: 'text' },
            { key: 'is_paid', label: 'Paid', type: 'boolean' },
            { key: 'price', label: 'Price', type: 'money' },
            { key: 'group_id', label: 'Group', type: 'badge', ref: 'service_groups' },
            { key: 'status_id', label: 'Status', type: 'badge', ref: 'request_statuses' },
        ],
        filters: [{ key: 'q', label: 'Search', type: 'text' }],
        formFields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            { name: 'slug', label: 'Slug', type: 'text', required: true },
            { name: 'description', label: 'Description', type: 'textarea' },
            { name: 'is_paid', label: 'Paid', type: 'switch' },
            { name: 'price', label: 'Price', type: 'money' },
            { name: 'price_title', label: 'Price Title', type: 'text' },
            { name: 'status_id', label: 'Status', type: 'select', options: 'references:request_statuses' },
            { name: 'group_id', label: 'Group', type: 'select', options: 'references:service_groups' },
            { name: 'form_type', label: 'Form Type', type: 'text' },
            { name: 'form_id', label: 'Form ID', type: 'number' },
        ],
    },

    'admin.service-fields': {
        title: 'Service Fields',
        singular: 'Field',
        icon: 'pi pi-list',
        routeBase: '/admin/service-fields',
        api: adminServiceFieldsApi,
        perPage: 50,
        canCreate: true, canEdit: true, canDelete: true,
        columns: [
            { key: 'title', label: 'Title', type: 'text' },
            { key: 'slug', label: 'Slug', type: 'text' },
            { key: 'entity', label: 'Entity', type: 'text' },
            { key: 'type', label: 'Type', type: 'badge' },
            { key: 'section', label: 'Section', type: 'text' },
        ],
        filters: [],
        formFields: [
            { name: 'title', label: 'Title', type: 'text', required: true },
            { name: 'slug', label: 'Slug', type: 'text', required: true },
            { name: 'entity', label: 'Entity', type: 'text', required: true },
            { name: 'type', label: 'Type', type: 'text' },
            { name: 'section', label: 'Section', type: 'text' },
            { name: 'placeholder', label: 'Placeholder', type: 'text' },
            { name: 'tooltip', label: 'Tooltip', type: 'textarea' },
            { name: 'description', label: 'Description', type: 'textarea' },
            { name: 'default_value', label: 'Default Value', type: 'text' },
            { name: 'reference_code', label: 'Reference Code', type: 'text' },
            { name: 'icon', label: 'Icon', type: 'text' },
            { name: 'classes', label: 'CSS Classes', type: 'text' },
        ],
    },

    'admin.service-groups': {
        title: 'Service Groups',
        singular: 'Group',
        icon: 'pi pi-sitemap',
        routeBase: '/admin/service-groups',
        api: adminServiceGroupsApi,
        perPage: 50,
        canCreate: true, canEdit: true, canDelete: true,
        columns: [
            { key: 'name', label: 'Name', type: 'text' },
            { key: 'slug', label: 'Slug', type: 'text' },
            { key: 'description', label: 'Description', type: 'text' },
            { key: 'is_active', label: 'Active', type: 'boolean' },
        ],
        filters: [],
        formFields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            { name: 'slug', label: 'Slug', type: 'text', required: true },
            { name: 'description', label: 'Description', type: 'textarea' },
            { name: 'is_active', label: 'Active', type: 'switch' },
        ],
    },

    'admin.sub-plans': {
        title: 'Subscription Plans',
        singular: 'Plan',
        icon: 'pi pi-tags',
        routeBase: '/admin/sub-plans',
        api: adminSubPlansApi,
        canCreate: true, canEdit: true, canDelete: true,
        columns: [
            { key: 'name', label: 'Name', type: 'text' },
            { key: 'slug', label: 'Slug', type: 'text' },
            { key: 'price', label: 'Price', type: 'money' },
            { key: 'price_per_driver', label: 'Per Driver', type: 'money' },
            { key: 'duration', label: 'Duration', type: 'text' },
            { key: 'is_custom_price', label: 'Custom', type: 'boolean' },
        ],
        filters: [],
        formFields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            { name: 'slug', label: 'Slug', type: 'text', required: true },
            { name: 'price', label: 'Price', type: 'money' },
            { name: 'price_per_driver', label: 'Price Per Driver', type: 'money' },
            { name: 'is_custom_price', label: 'Custom Price', type: 'switch' },
            { name: 'drivers_amount_from', label: 'Drivers From', type: 'number' },
            { name: 'drivers_amount_to', label: 'Drivers To', type: 'number' },
            { name: 'discount', label: 'Discount (%)', type: 'number' },
            { name: 'duration', label: 'Duration', type: 'text' },
            { name: 'short_description', label: 'Short Description', type: 'textarea' },
            { name: 'description', label: 'Description', type: 'textarea' },
        ],
    },

    'admin.sub-requests': {
        title: 'Subscription Requests',
        singular: 'Request',
        icon: 'pi pi-envelope',
        routeBase: '/admin/sub-requests',
        api: adminSubRequestsApi,
        canCreate: true, canEdit: true, canDelete: true,
        columns: [
            { key: 'id', label: 'ID', type: 'number' },
            { key: 'user_id', label: 'User', type: 'number' },
            { key: 'subscription_id', label: 'Plan', type: 'number' },
            { key: 'status_id', label: 'Status', type: 'badge', ref: 'request_statuses' },
            { key: 'created_at', label: 'Created', type: 'datetime' },
        ],
        filters: [{ key: 'status_id', label: 'Status', type: 'select', options: 'references:request_statuses' }],
        formFields: [
            // user/subscription ids are only accepted on create; update takes
            // request_details + status_id only, so hide them when editing.
            { name: 'user_id', label: 'User ID', type: 'number', required: true, createOnly: true },
            { name: 'subscription_id', label: 'Plan ID', type: 'number', createOnly: true },
            { name: 'user_subscription_id', label: 'User Subscription ID', type: 'number', createOnly: true },
            { name: 'request_details', label: 'Request Details', type: 'textarea' },
            { name: 'status_id', label: 'Status', type: 'select', options: 'references:request_statuses' },
        ],
    },

    'admin.user-subscriptions': {
        title: 'User Subscriptions',
        singular: 'Subscription',
        icon: 'pi pi-users',
        routeBase: '/admin/user-subscriptions',
        api: adminSubManagerApi,
        canCreate: true, canEdit: true, canDelete: true,
        columns: [
            { key: 'id', label: 'ID', type: 'number' },
            { key: 'user_id', label: 'User', type: 'number' },
            { key: 'subscription.name', label: 'Plan', type: 'text' },
            { key: 'drivers_number', label: 'Drivers', type: 'number' },
            { key: 'price', label: 'Price', type: 'money' },
            { key: 'status', label: 'Status', type: 'badge' },
            { key: 'next_date', label: 'Next', type: 'date' },
        ],
        filters: [{ key: 'status', label: 'Status', type: 'select', options: SUB_STATUS }],
        formFields: [
            // user_id is only accepted on create; the update endpoint ignores it.
            { name: 'user_id', label: 'User ID', type: 'number', required: true, createOnly: true },
            { name: 'subscription_id', label: 'Plan ID', type: 'number', required: true },
            { name: 'drivers_number', label: 'Number of Drivers', type: 'number' },
            { name: 'price_per_driver', label: 'Price Per Driver', type: 'money' },
            { name: 'discount', label: 'Discount (%)', type: 'number' },
            { name: 'status', label: 'Status', type: 'select', options: SUB_STATUS },
        ],
    },

    'admin.plan-fees': {
        title: 'Plan Fees',
        singular: 'Plan Fee',
        icon: 'pi pi-dollar',
        routeBase: '/admin/plan-fees',
        api: adminPlanFeesApi,
        canCreate: true, canEdit: true, canDelete: false, // API has no DELETE (405)
        columns: [
            { key: 'id', label: 'ID', type: 'number' },
            { key: 'name', label: 'Name', type: 'text' },
            { key: 'price', label: 'Price', type: 'money' },
            { key: 'discount', label: 'Discount', type: 'money' },
            { key: 'user_role_id', label: 'Role ID', type: 'number' },
        ],
        filters: [],
        formFields: [
            { name: 'name', label: 'Name', type: 'text', required: true },
            { name: 'price', label: 'Price', type: 'money', required: true },
            { name: 'discount', label: 'Discount', type: 'money' },
            { name: 'short_description', label: 'Short Description', type: 'textarea' },
            { name: 'description', label: 'Description', type: 'textarea' },
            { name: 'user_role_id', label: 'User Role ID', type: 'number' },
        ],
    },

    'admin.users': {
        title: 'Users',
        singular: 'User',
        icon: 'pi pi-user',
        routeBase: '/admin/users',
        api: adminUsersApi,
        canCreate: true, canEdit: true, canDelete: true,
        columns: [
            { key: 'fullname', label: 'Name', type: 'text' },
            { key: 'email', label: 'Email', type: 'email' },
            { key: 'phone', label: 'Phone', type: 'text' },
            { key: 'role', label: 'Role', type: 'badge' },
            { key: 'is_active', label: 'Active', type: 'boolean' },
            { key: 'created_at', label: 'Created', type: 'datetime' },
        ],
        filters: [{ key: 'q', label: 'Search name or email', type: 'text' }],
        formFields: [
            { name: 'firstname', label: 'First name', type: 'text', required: true },
            { name: 'lastname', label: 'Last name', type: 'text' },
            { name: 'email', label: 'Email', type: 'email', required: true },
            { name: 'phone', label: 'Phone', type: 'text' },
            { name: 'birthday', label: 'Birthday', type: 'date' },
            { name: 'password', label: 'Password', type: 'password', required: true },
            // show endpoint returns role as { id, title, slug }; bind the slug.
            { name: 'role', label: 'Role', type: 'select', options: ROLE_OPTIONS, required: true, valuePath: 'role.slug' },
        ],
    },

    'admin.notifications-manage': {
        title: 'Notifications',
        singular: 'Notification',
        icon: 'pi pi-bell',
        routeBase: '/admin/notifications-manage',
        api: adminNotificationsApi,
        canCreate: true, canEdit: true, canDelete: true,
        columns: [
            { key: 'title', label: 'Title', type: 'text' },
            { key: 'message', label: 'Message', type: 'text' },
            { key: 'type', label: 'Type', type: 'badge' },
            { key: 'status', label: 'Status', type: 'badge' },
            { key: 'created_at', label: 'Created', type: 'datetime' },
        ],
        filters: [{ key: 'q', label: 'Search by title', type: 'text' }],
        formFields: [
            { name: 'title', label: 'Title', type: 'text', required: true },
            { name: 'message', label: 'Message', type: 'textarea', required: true },
            { name: 'type', label: 'Type', type: 'text' },
            { name: 'status', label: 'Status', type: 'text' },
            { name: 'user_id_to', label: 'Recipient User ID', type: 'number' },
        ],
    },
};

export default sections;
