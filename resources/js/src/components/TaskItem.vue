<script setup>
    const props = defineProps(['task']);
    const emit = defineEmits(['toggle', 'edit', 'delete']);

    const toggleTask = () => emit('toggle', props.task);
    const editTask = () => emit('edit', props.task);
    const deleteTask = () => emit('delete', props.task.id);
</script>

<template>
    <li class="mb-2 p-2 border rounded">
    <div class="flex items-center justify-between">
        <div>
            <input 
                type="checkbox" 
                :checked="task.is_completed" 
                @change="toggleTask" 
                class="mr-2"
            >
            <span :class="{ 'line-through': task.is_completed }">{{ task.title }}</span>
        </div>
        <div>
            <button @click="editTask" class="text-blue-500 mr-2">Редактировать</button>
            <button @click="deleteTask" class="text-red-500">Удалить</button>
        </div>
    </div>
    </li>
</template>