<script setup>
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import { saferwebApi } from '@/api';
import SaferwebTable from './SaferwebTable.vue';

const inspectionColumns = [
    { key: 'report_number', label: 'Report #', type: 'text' },
    { key: 'report_date', label: 'Report Date', type: 'date' },
    { key: 'inspection_level', label: 'Level', type: 'text' },
    { key: 'report_state', label: 'State', type: 'badge' },
    { key: 'unit_vin', label: 'VIN', type: 'text' },
    { key: 'dot_number', label: 'DOT #', type: 'text' },
];
const inspectionDetail = [
    'id', 'report_number', 'report_sequence_number', 'report_date', 'inspection_level',
    'report_state', 'unit_vin', 'dot_number', 'vehicle_id', 'company_id', 'unique_id', 'created_at', 'api_data',
];

const crashColumns = [
    { key: 'report_number', label: 'Report #', type: 'text' },
    { key: 'report_date', label: 'Report Date', type: 'date' },
    { key: 'report_state', label: 'State', type: 'badge' },
    { key: 'unit_vin', label: 'VIN', type: 'text' },
    { key: 'dot_number', label: 'DOT #', type: 'text' },
    { key: 'total_injuries', label: 'Injuries', type: 'text' },
    { key: 'total_fatalities', label: 'Fatalities', type: 'text' },
];
const crashDetail = [
    'id', 'report_number', 'report_sequence_number', 'report_date', 'report_state', 'unit_vin',
    'dot_number', 'total_injuries', 'total_fatalities', 'vehicle_id', 'company_id', 'created_at', 'api_data',
];
</script>

<template>
    <div class="surface-card surface-card-pad">
        <Tabs value="inspections">
            <TabList>
                <Tab value="inspections">Inspections</Tab>
                <Tab value="crashes">Crashes</Tab>
            </TabList>
            <TabPanels>
                <TabPanel value="inspections">
                    <SaferwebTable
                        :listFn="saferwebApi.inspections"
                        :getFn="saferwebApi.inspection"
                        :columns="inspectionColumns"
                        :detailFields="inspectionDetail"
                    />
                </TabPanel>
                <TabPanel value="crashes">
                    <SaferwebTable
                        :listFn="saferwebApi.crashes"
                        :getFn="saferwebApi.crash"
                        :columns="crashColumns"
                        :detailFields="crashDetail"
                    />
                </TabPanel>
            </TabPanels>
        </Tabs>
    </div>
</template>
