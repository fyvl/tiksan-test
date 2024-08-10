<script setup>
    import { ref } from 'vue';

    const emit = defineEmits(['add-task']);
    const newTask = ref('');

    const addTask = () => {
        if (newTask.value.trim() === '') return;
        emit('add-task', newTask.value);
        newTask.value = '';
    };
</script>

<template>
    <form @submit.prevent="addTask" class="mb-4">
    <input 
        v-model="newTask" 
        type="text" 
        placeholder="Новая задача" 
        class="border p-2 mr-2"
    >
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Добавить</button>
    </form>
</template>