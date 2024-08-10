<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';
    import TaskItem from './TaskItem.vue';
    import TaskForm from './TaskForm.vue';
    import EditTaskModal from './EditTaskModal.vue';
    
    const tasks = ref([]);
    const editingTask = ref(null);

    const fetchTasks = async () => {
        try {
            const response = await axios.get('/api/tasks');
            tasks.value = response.data;
        } catch (error) {
            console.error('Ошибка при загрузке задач:', error);
        }
    };

    const addTask = async (title) => {
        try {
            const response = await axios.post('/api/tasks', { title });
            tasks.value.push(response.data);
        } catch (error) {
            console.error('Ошибка при добавлении задачи:', error);
        }
    };

    const deleteTask = async (id) => {
        try {
            await axios.delete(`/api/tasks/${id}`);
            tasks.value = tasks.value.filter(task => task.id !== id);
        } catch (error) {
            console.error('Ошибка при удалении задачи:', error);
        }
    };

    const toggleTask = async (task) => {
        try {
            const response = await axios.put(`/api/tasks/${task.id}`, {
            ...task,
            is_completed: !task.is_completed
            });
            const index = tasks.value.findIndex(t => t.id === task.id);
            tasks.value[index] = response.data;
        } catch (error) {
            console.error('Ошибка при обновлении статуса задачи:', error);
        }
    };

    const editTask = (task) => {
        editingTask.value = { ...task };
    };

    const saveEdit = async (updatedTask) => {
        try {
            const response = await axios.put(`/api/tasks/${updatedTask.id}`, updatedTask);
            const index = tasks.value.findIndex(t => t.id === updatedTask.id);
            tasks.value[index] = response.data;
            editingTask.value = null;
        } catch (error) {
            console.error('Ошибка при сохранении изменений:', error);
        }
    };

    const cancelEdit = () => {
        editingTask.value = null;
    };

    onMounted(fetchTasks);
</script>

<template>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Список задач</h1>

        <TaskForm @add-task="addTask" />

        <ul>
            <TaskItem 
                v-for="task in tasks" 
                :key="task.id" 
                :task="task"
                @toggle="toggleTask"
                @edit="editTask"
                @delete="deleteTask"
            />
        </ul>

        <EditTaskModal 
            v-if="editingTask" 
            :task="editingTask" 
            @save="saveEdit"
            @cancel="cancelEdit"
        />
    </div>
</template>