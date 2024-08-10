<script setup>
    import { ref, watch } from 'vue';

    const props = defineProps({
        task: Object,
        showModal: Boolean
    });
    const emit = defineEmits(['save', 'cancel']);

    const form = ref({
        title: '',
        description: ''
    });

    watch(() => props.task, (newTask) => {
        if (newTask) {
            form.value = { ...newTask };
        } else {
            form.value = { title: '', description: '' };
        }
    });

    const saveTask = () => {
        emit('save', form.value);
        clearForm();
    };

    const cancel = () => {
        emit('cancel');
        clearForm();
    };

    const clearForm = () => {
        form.value = { title: '', description: '' };
    }
</script>

<template>
    <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">{{ task ? 'Редактировать задачу' : 'Добавить задачу' }}</h3>
            <input 
                v-model="form.title" 
                type="text" 
                placeholder="Название задачи" 
                class="border p-2 w-full mb-4"
            >
            <input 
                v-model="form.description" 
                type="text" 
                placeholder="Описание задачи" 
                class="border p-2 w-full mb-4"
            >
            <div class="flex justify-end">
                <button @click="saveTask" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">
                    {{ task ? 'Сохранить' : 'Добавить' }}
                </button>
                <button @click="cancel" class="bg-gray-300 px-4 py-2 rounded">Отмена</button>
            </div>
        </div>
    </div>
</template>