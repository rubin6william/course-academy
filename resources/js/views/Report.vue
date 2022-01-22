<template>
    <div class="container px-6 py-4 h-screen">
        <h1 class="mb-4">Course Report</h1>

        <ui-table url="/api/enrolments"
                  sort-by="user"
                  :columns="columns">
            <template v-slot:cell(completion_status)="row">
                <div class="flex gap-2 items-center">
                    <div class="dot"
                         :class="[
                                 {'bg-blue-500': (row.item.completion_status === 'not_started')},
                                 {'bg-yellow-500': (row.item.completion_status === 'in_progress')},
                                 {'bg-green-500': (row.item.completion_status === 'completed')}
                             ]">
                    </div>
                    <div>
                        {{ row.item.completion_status_label }}
                    </div>
                </div>
            </template>

            <template v-slot:additional-info="{ response }">
                <div class="flex gap-6 px-6 py-4 shadow mt-2 mb-4">
                    <div class="flex gap-2">
                        <span class="text-gray-400">Total Users: </span>
                        <span class="font-medium">{{ response.counts.users }}</span>
                    </div>

                    <div class="flex gap-2">
                        <span class="text-gray-400">Total Courses: </span>
                        <span class="font-medium">{{ response.counts.courses }}</span>
                    </div>

                    <div class="flex gap-2">
                        <span class="text-gray-400">Not Started: </span>
                        <span class="font-medium">{{ response.counts.not_started }}</span>
                    </div>

                    <div class="flex gap-2">
                        <span class="text-gray-400">In Progress: </span>
                        <span class="font-medium">{{ response.counts.in_progress }}</span>
                    </div>

                    <div class="flex gap-2">
                        <span class="text-gray-400">Completed: </span>
                        <span class="font-medium">{{ response.counts.completed }}</span>
                    </div>
                </div>
            </template>
        </ui-table>
    </div>
</template>

<script>
import {ref} from 'vue'
import UiTable from '../components/UiTable'

export default {
    name: 'Report',
    components: {
        UiTable,
    },
    setup() {
        const columns = [
            {
                key: 'user',
                label: 'User',
                sortable: true,
                searchable: true,
            },
            {
                key: 'course',
                label: 'Course',
                sortable: true,
                searchable: true,
            },
            {
                key: 'completion_status',
                label: 'Completion Status',
                sortable: true,
                searchable: true,
                type: 'select',
                values: [
                    {value: 'not_started', text: 'Not Started'},
                    {value: 'in_progress', text: 'In Progress'},
                    {value: 'completed', text: 'Completed'},
                ]
            }
        ]

        return {
            columns
        }
    }
}
</script>

<style scoped>
    .dot {
        @apply h-2 w-2 rounded-full;
    }
</style>
