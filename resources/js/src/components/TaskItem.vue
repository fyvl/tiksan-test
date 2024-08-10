<script setup>
    import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';

    const props = defineProps(['task']);
    const emit = defineEmits(['toggle', 'edit', 'delete']);

    const toggleTask = () => emit('toggle', props.task);
    const editTask = () => emit('edit', props.task);
    const deleteTask = () => emit('delete', props.task.id);

    function formatDate(date) {
        if (!date) return 'N/A'
        return new Date(date).toLocaleDateString('ru-RU', {
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        })
    }
</script>

<template>
    <li class="mb-4 p-4 border rounded shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <input 
                    type="checkbox" 
                    :checked="task.is_completed" 
                    @change="toggleTask"
                    class="mr-2"
                >
                <span :class="{ 'line-through text-gray-500': task.is_completed, 'text-black': !task.is_completed, 'text-lg': true }">{{ task.title }}</span>
                <div v-if="task.description" class="mt-2 text-gray-600">
                    {{ task.description }}
                </div>
            </div>
            <div class="flex space-x-2">
                <button 
                    @click="editTask" 
                    class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
                    aria-label="Edit task"
                >
                    <PencilIcon class="w-5 h-5"/>
                </button>
                <button 
                    @click="deleteTask" 
                    class="p-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
                    aria-label="Delete task"
                >
                    <TrashIcon class="w-5 h-5"/>
                </button>
            </div>
        </div>
        <div class="mt-2 text-gray-500 text-sm">
            <div>Создано: {{ formatDate(task.created_at) }}</div>
            <div>Обновлено: {{ formatDate(task.updated_at) }}</div>
        </div>
    </li>
  </template>