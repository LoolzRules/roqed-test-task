<script setup>
import {onMounted, onUnmounted, ref} from 'vue'
import Modal from "@/Components/Modal.vue";

defineProps({
    label: String,
    modelValue: File,
    errors: Array,
    name: String,
    required: Boolean
})

const emit = defineEmits(['update:modelValue'])

const input = ref(null)
const isDragging = ref(false)
const dropToTarget = ref(false)

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
})

defineExpose({ focus: () => input.value.focus() })

function windowDragover(e) {
    e.preventDefault()
    isDragging.value = true
}
function dragover() {
    dropToTarget.value = true
}
function dragleave() {
    dropToTarget.value = false
}
function drop(e) {
    emit('update:modelValue', e.dataTransfer.files[0])
    isDragging.value = false
    dropToTarget.value = false
}

onMounted(() => {
    window.addEventListener('dragover', windowDragover)
})

onUnmounted(() => {
    window.removeEventListener('dragover', windowDragover)
})
</script>

<template>
    <div class="w-full flex flex-col">
        <div class="flex">
            <label
                class="w-full flex flex-col relative cursor-pointer">
                <span v-if="label">
                    {{ label }}
                    <span v-if="required" class="text-red-500">*</span>
                </span>
                <span class="bg-white rounded-md font-medium hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 border px-3 py-2">
                    {{ modelValue?.name ?? 'Append a file or drop it here' }}
                </span>
                <input
                    :name="name"
                    ref="input"
                    type="file"
                    class="sr-only"
                    @change="$emit('update:modelValue', $event.target.files[0])" />
            </label>
            <button
                v-if="modelValue"
                class="ml-2 custom-button negative"
                type="button"
                @click.prevent="$emit('update:modelValue', null)"
                title="Remove file">
                Remove
            </button>
        </div>
        <span v-if="errors" class="text-sm text-red-500 mt-1">{{ errors.join(', ') }}</span>

        <Modal :show="isDragging" @close="isDragging = false">
            <div
                class="w-full h-[200px] flex justify-center items-center uppercase"
                :class="{ 'bg-blue-100' : dropToTarget }"
                @dragover.prevent="dragover"
                @dragleave.prevent="dragleave"
                @drop.prevent="drop"
                @click="isDragging = false"
            >
                Drop the file here
            </div>
        </Modal>
    </div>

</template>
