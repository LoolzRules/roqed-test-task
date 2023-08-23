<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    label: String,
    modelValue: String,
    errors: Array,
    required: Boolean,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="w-full flex flex-col">
        <label class="text-small flex flex-col">
            <span v-if="label" class="mb-1">
                {{ label }}
                <span v-if="required" class="text-red-500">*</span>
            </span>
            <input
                type="text"
                ref="input"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            >
        </label>
        <span v-if="errors" class="text-sm text-red-500 mt-1">{{ errors.join(', ') }}</span>
    </div>
</template>
