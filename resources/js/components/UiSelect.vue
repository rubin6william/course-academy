<template>
    <select v-model="selected"
            class="focus:outline-none focus:outline-blue-500 rounded">
        <option :value="option.value"
                :key="option.value"
                v-for="option in options">
            {{ option.text }}
        </option>
    </select>
</template>

<script>
import {ref, watch} from 'vue'

export default {
    name: 'UiSelect',
    props: {
        modelValue: Number|String,
        options: {
            required: true,
            type: Array
        }
    },
    emits: [
        'update:modelValue'
    ],
    setup(props, context) {
        const selected = ref(props.modelValue)

        watch(selected, (value) => {
            context.emit('update:modelValue', value)
        })

        return {
            selected
        }
    }
}
</script>

<style scoped>

</style>
