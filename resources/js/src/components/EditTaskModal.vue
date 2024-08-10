<script setup>
    import { ref, watch } from 'vue';

    const props = defineProps(['task']);
    const emit = defineEmits(['save', 'cancel']);

    const editedTask = ref({ ...props.task });

    watch(() => props.task, (newTask) => {
        editedTask.value = { ...newTask };
    });

    const saveEdit = () => {
        emit('save', editedTask.value);
    };

    const cancelEdit = () => {
        emit('cancel');
    };
</script>

<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">Редактировать задачу</h3>
            <input 
                v-model="editedTask.title" 
                type="text" 
                class="border p-2 w-full mb-4"
            >
            <div class="flex justify-end">
                <button @click="saveEdit" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Сохранить</button>
                <button @click="cancelEdit" class="bg-gray-300 px-4 py-2 rounded">Отмена</button>
            </div>
        </div>
    </div>
</template> 