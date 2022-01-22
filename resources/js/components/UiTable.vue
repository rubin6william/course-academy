<template>
    <div class="flex flex-col gap-4 bg-blue-100 px-6 py-2 rounded mb-4">
        <div class="font-medium">Filters</div>
        <div class="flex flex-col sm:flex-row w-full gap-4">
            <template v-for="column in searchableColumns">
                <select v-if="column.type === 'select'"
                        v-model="filters[column.key]">
                    <option value="" class="text-gray-400">{{ `Search ${column.label}` }}</option>
                    <option :value="option.value" v-for="option in column.values">{{ option.text }}</option>
                </select>

                <input v-else
                       type="text"
                       :placeholder="`Search ${column.label}`"
                       v-model="filters[column.key]"/>
            </template>
        </div>
        <button class="self-start bg-white px-4 py-1 rounded border border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:font-medium"
                @click="resetFilters">
            Reset
        </button>
    </div>

    <div class="flex gap-4">
        <slot name="additional-info" :response="localResponse"></slot>
    </div>

    <div class="md:hidden text-sm mt-2">
        <select v-model="sorting"
                class="mb-4 w-full">
            <option value="">Sort By</option>
            <template v-for="column in columns">
                <template v-if="column.sortable">
                    <option :value="`${column.key}-asc`">{{ column.label }} (Ascending)</option>
                    <option :value="`${column.key}-desc`">{{ column.label }} (Descending)</option>
                </template>
            </template>
        </select>

        <div class="flex flex-col gap-4">
            <div class="flex px-6 py-2 shadow rounded bg-white even:bg-blue-100"
                 v-for="item in items"
                 :key="item.id">
                <div class="flex flex-col gap-4 py-2">
                    <div v-for="column in columns"
                         :key="`${item.id}_${item[column.key]}`">
                        <div class="flex gap-4">
                            <span class="text-gray-400">{{ column.label }}:</span>
                            <span class="font-medium">
                            <slot :name="`cell(${column.key})`"
                                  :item="item">{{ item[column.key] }}
                            </slot>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col hidden md:flex">
        <table class="sm:table-auto text-left w-full text-sm">
            <thead>
            <tr class="border-b border-gray-100">
                <th class="px-6 py-4 text-slate-400"
                    :class="{'cursor-pointer': column.sortable}"
                    :key="column.key"
                    v-for="column in columns">
                    <div v-if="column.sortable"
                         class="flex justify-between"
                         @click="updateSorting(column)">
                        {{ column.label }}
                        <div v-if="(column.key === sortBy)">
                            <svg v-if="sortOrder === 'asc'"
                                 xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                            </svg>

                            <svg v-else-if="sortOrder === 'desc'"
                                 xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                            </svg>
                        </div>
                    </div>
                    <span v-else>
                        {{ column.label }}
                    </span>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr class="bg-white even:bg-gray-100 hover:bg-gray-200 text-gray-900"
                v-for="item in items"
                :key="item.id">
                <td class="px-6 py-4"
                    v-for="column in columns"
                    :key="`${item.id}_${item[column.key]}`">
                    <slot :name="`cell(${column.key})`"
                          :item="item">{{ item[column.key] }}
                    </slot>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <ui-pagination v-model:per-page="perPage"
                   v-model:current-page="currentPage"
                   :total="total">
    </ui-pagination>
</template>

<script>
import _ from 'lodash'
import { onMounted, ref, inject, watch, computed } from 'vue'
import UiPagination from './UiPagination'

export default {
    name: 'UiTable',
    components: {
        UiPagination,
    },
    props: {
        columns: {
            type: Array,
            required: true,
            default: []
        },
        url: {
            type: String,
            required: true,
        },
        sortBy: {
            type: String,
            default: 'id',
        },
        sortOrder: {
            type: String,
            default: 'desc',
        }
    },
    setup (props) {
        const api = inject('api')
        const items = ref([])
        const loading = ref(false)
        const total = ref(0)
        const currentPage = ref(1)
        const perPage = ref(10)
        const sortBy = ref(props.sortBy)
        const sortOrder = ref(props.sortOrder)
        const sorting = ref('')
        const filters = ref({})
        const localResponse = ref({})

        const fetchData = () => {
            loading.value = true

            api
                .get(props.url, {
                    per_page: perPage.value,
                    current_page: currentPage.value,
                    sort_by: sortBy.value,
                    sort_order: sortOrder.value,
                    filters: filters.value,
                })
                .then(response => {
                    localResponse.value = _.get(response, 'data', {})
                    items.value = _.get(response, 'data.data', [])
                    currentPage.value = Number(_.get(response, 'data.current_page', 1))
                    total.value = Number(_.get(response, 'data.total', 0))
                    perPage.value = Number(_.get(response, 'data.per_page', 10))
                })
                .catch(error => {
                    console.log(error)
                })
                .finally(() => loading.value = false)
        }

        const updateSorting = (column) => {
            sortBy.value = column.key
            sortOrder.value = (sortOrder.value === 'desc') ? 'asc' : 'desc'
            fetchData()
        }

        const searchableColumns = computed(() => {
            return _.filter(props.columns, {searchable: true})
        })

        const resetFilters = () => {
            searchableColumns.value.forEach(column => {
                filters.value[column.key] = ''
            })
        }

        onMounted(() => {
            resetFilters()
            fetchData()
        })

        watch(perPage, () => {
            fetchData()
        })

        watch(currentPage, () => {
            fetchData()
        })

        watch(filters.value, () => {
            fetchData()
        })

        watch(sorting, (value) => {
            if (!_.size(value)) {
                return
            }

            const parts = value.split('-')

            sortBy.value = parts[0]
            sortOrder.value = parts[1]

            fetchData()
        })

        return {
            localResponse,
            items,
            loading,
            total,
            currentPage,
            perPage,
            sortBy,
            sortOrder,
            sorting,
            filters,
            searchableColumns,
            updateSorting,
            resetFilters,
        }
    }
}
</script>

<style scoped>
    select {
        @apply border border-gray-100 rounded focus:outline-none focus:outline-blue-400 px-6 py-2;
    }

    input {
        @apply px-6 py-2 border border-gray-100 rounded focus:outline-none focus:outline-blue-400;
    }
</style>
