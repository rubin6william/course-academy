<template>
    <div class="px-4 py-3 flex items-center justify-between sm:px-6 w-full">
        <div class="flex-1 flex justify-between sm:hidden">
            <a href=""
               class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
               @click.prevent="previous">
                Previous
            </a>
            <a href=""
               class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
               @click.prevent="next">
                Next
            </a>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <p class="text-sm text-gray-700 flex gap-4">
                Viewing
                <ui-select v-model="localPerPage"
                           :options="localPageOptions">
                </ui-select>
                results
            </p>

            <p class="text-sm text-gray-700">
                Show
                <span class="font-medium">{{ from }}</span>
                to
                <span class="font-medium">{{ to }}</span>
                of
                <span class="font-medium">{{ total }}</span>
                results
            </p>

            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm" aria-label="Pagination">
                    <a href=""
                       aria-current="First page"
                       class="z-10 relative inline-flex items-center px-4 py-2 border text-sm font-medium border-gray-100 text-gray-500"
                       :class="{'disabled': (localCurrentPage === 1)}"
                       @click.prevent="showPage(1)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>

                    <a href=""
                       aria-current="page"
                       class="z-10 relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                       :class="[(page === localCurrentPage) ? 'bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-100 text-gray-500']"
                       v-for="page in pages"
                       :key="`page-${page}`"
                       @click.prevent="showPage(page)">
                        {{ page }}
                    </a>

                    <a href=""
                       aria-current="First page"
                       class="z-10 relative inline-flex items-center px-4 py-2 border text-sm font-medium border-gray-100 text-gray-500"
                       :class="{'disabled': (localCurrentPage === totalPages)}"
                       @click.prevent="showPage(totalPages)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash'
import {computed, ref, watch} from 'vue'
import UiSelect from './UiSelect'

export default {
    name: 'UiPagination',
    components: {
        UiSelect,
    },
    props: {
        currentPage: {
            type: Number,
            default: 1,
        },
        perPage: {
            type: Number,
            default: 10,
        },
        total: {
            required: true,
            type: Number,
        },
        pageOptions: {
            type: Array,
            default() {
                return [
                    10,
                    25,
                    50,
                    100
                ]
            }
        }
    },
    emits: [
        'update:perPage',
        'update:currentPage',
    ],
    setup(props, context) {
        const localPerPage = ref(props.perPage)
        const localCurrentPage = ref(props.currentPage)

        const from = computed(() => {
            return !props.total ?
                0 :
                (localPerPage.value * (localCurrentPage.value - 1)) + 1
        })

        const to = computed(() => {
            return !props.total ?
                0 :
                (localPerPage.value * localCurrentPage.value)
        })

        const totalPages = computed(() => {
            if (localPerPage.value > 0) {
                return Math.ceil(props.total / localPerPage.value)
            }

            return 0
        })

        const localPageOptions = computed(() => {
            const pageOptions = []

            props.pageOptions.forEach(page => {
                pageOptions.push({value: page, text: page})
            })

            return pageOptions
        })

        const pages = computed(() => {
            const limit = 5

            const from1 = Number(localCurrentPage.value) - Math.round(limit / 2) + 1
            const from2 = Number(totalPages.value) + 1 - limit
            const from = Math.max(
                Math.min(from1, from2),
                1,
            )

            const to = Math.min(from + limit - 1, totalPages.value)

            return _.range(from, to + 1).map((page) => {
                if (((page === from) && from > 1) || ((page === to) && to < totalPages.value)) {
                    return '...'
                }

                return page
            })
        })

        const showPage = (page) => {
            if (page !== '...') {
                localCurrentPage.value = page
            }
        }

        const previous = () => {
            if (localCurrentPage.value === 1) {
                return
            }

            localCurrentPage.value--
        }

        const next = () => {
            if (localCurrentPage.value === totalPages.value) {
                return
            }

            localCurrentPage.value++
        }

        watch(localPerPage, (value) => {
            context.emit('update:perPage', value)
        })

        watch(localCurrentPage, (value) => {
            context.emit('update:currentPage', value)
        })

        return {
            from,
            to,
            pages,
            localPerPage,
            localCurrentPage,
            localPageOptions,
            totalPages,
            showPage,
            previous,
            next,
        }
    }
}
</script>

<style scoped>

</style>
