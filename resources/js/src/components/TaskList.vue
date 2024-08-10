<script setup>
    import { ref, onMounted } from 'vue';
    import axios from 'axios';
    import TaskItem from './TaskItem.vue';
    import TaskForm from './TaskForm.vue';
    import { PlusIcon, ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';
    
    const tasks = ref([]);
    const editingTask = ref(null);
    const showAddModal = ref(false);

    const currentPage = ref(1);
    const lastPage = ref(1);

    const fetchTasks = async (page = 1) => {
        try {
            const response = await axios.get(`/api/tasks?page=${page}`);
            tasks.value = response.data.data.sort((a, b) => {
                return new Date(b.updated_at) - new Date(a.updated_at);
            });
            currentPage.value = response.data.current_page;
            lastPage.value = response.data.last_page;
            console.log(tasks.value);
        } catch (error) {
            console.error('Ошибка при загрузке задач:', error);
        }
    };

    const openAddModal = () => {
        showAddModal.value = true;
    };

    const handleSave = async (task) => {
        if (task.id) {
            try {
                const response = await axios.put(`/api/tasks/${task.id}`, task);
                const index = tasks.value.findIndex(t => t.id === task.id);
                tasks.value[index] = response.data;
            } catch (error) {
                console.error('Ошибка при сохранении изменений:', error);
            }
        } else {
            try {
                const response = await axios.post('/api/tasks', task);
                tasks.value.push(response.data);
                fetchTasks();
            } catch (error) {
                console.error('Ошибка при добавлении задачи:', error);
            }
        }
        showAddModal.value = false;
        editingTask.value = null;
    };

    const handleCancel = () => {
        showAddModal.value = false;
        editingTask.value = null;
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
        showAddModal.value = true;
    };

    onMounted(fetchTasks);
</script>

<template>
    <div class="flex items-start justify-center min-h-screen pt-16 bg-gray-100">
        <div class="w-full max-w-xl p-4 bg-white shadow-md rounded flex flex-col" style="height: 918px;">
            <h1 class="text-2xl font-bold mb-4 text-center">Список задач</h1>

            <button @click="openAddModal" class="flex items-center bg-green-500 text-white px-4 py-2 rounded mb-4 mx-auto">
                <PlusIcon class="w-6 h-6 mr-1"/>
                <span>Добавить задачу</span>
            </button>

            <TaskForm 
                :showModal="showAddModal || editingTask"
                :task="editingTask"
                @save="handleSave"
                @cancel="handleCancel"
            />

            <div class="flex flex-col flex-grow">
                <ul class="flex-grow">
                    <TaskItem 
                        v-for="task in tasks" 
                        :key="task.id" 
                        :task="task"
                        @toggle="toggleTask"
                        @edit="editTask"
                        @delete="deleteTask"
                    />
                </ul>

                <div class="flex justify-center">
                    <button
                        @click="fetchTasks(currentPage - 1)"
                        :class="{ 'bg-gray-300': currentPage > 1, 'bg-gray-500 cursor-not-allowed': currentPage <= 1 }"
                        :disabled="currentPage <= 1"
                        class="px-4 py-2 rounded-l flex items-center"
                    >
                        <ChevronLeftIcon class="w-5 h-5 mr-1" />
                    </button>
                    <span class="px-4 py-2">{{ currentPage }} из {{ lastPage }}</span>
                    <button
                        @click="fetchTasks(currentPage + 1)"
                        :class="{ 'bg-gray-300': currentPage < lastPage, 'bg-gray-500 cursor-not-allowed': currentPage >= lastPage }"
                        :disabled="currentPage >= lastPage"
                        class="px-4 py-2 rounded-r flex items-center"
                    >
                        <ChevronRightIcon class="w-5 h-5 ml-1" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
