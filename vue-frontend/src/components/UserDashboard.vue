<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md transform transition-all duration-500 hover:scale-105">
      <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Todo List</h2>
      <div v-if="user" class="text-center mb-6">
        <p class="text-lg text-gray-700">Welcome, {{ user.name }}</p>
      </div>

      <!-- Search Bar -->
      <div class="mb-6 flex space-x-2">
        <input
          v-model="pagination.searchQuery"
          type="text"
          placeholder="Search tasks..."
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
          @keyup.enter="searchTasks"
        />
        <button
          @click="searchTasks"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all"
        >
          Search
        </button>
      </div>
      <!-- Task Type Filter -->
      <div class="mb-6">
        <select
          v-model="pagination.selectedType"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
          @change="searchTasks"
        >
          <option value="" disabled>Select ToDo Type Filter</option>
          <option value="">All</option>
          <option v-for="newtype in taskTypes" :key="newtype.id" :value="Number(newtype.id)">
            {{ newtype.name }}
          </option>
        </select>
      </div>
      <hr>
      <br>
      <!-- Add Task Form -->
      <!DOCTYPE html>
      <html lang="en">
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Task Form</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
      </head>
      <body>
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Add ToDo</h2>
        <form @submit.prevent="addTask" class="mb-6">
        
          <div class="flex flex-col space-y-4">
            <!-- Task Title Input -->
            <input
              v-model="newTask.title"
              type="text"
              placeholder="Task Title"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
              required
            />

            <!-- Task Description Textarea -->
            <textarea
              v-model="newTask.description"
              placeholder="Task Description"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
            ></textarea>

            <select
                  v-model="newTask.type"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                >
                  <option value="" disabled>Select Task Type</option>
                  <option v-for="newtype in taskTypes" :key="newtype.id" :value="newtype.id">
                    {{ newtype.name }}
                  </option>
                </select>

            <!-- Add Task Button -->
            <button
              type="submit"
              class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all"
            >
              Add Task
            </button>
          </div>
        </form>
      </body>
      </html>

      <div v-if="loading" class="text-center text-gray-600">
        <p>Loading tasks...</p>
      </div>
      <div v-else>
      <!-- Task List -->
          <div v-if="tasks.length > 0" class="space-y-4">
            <div
              v-for="(task, index) in tasks"
              :key="index"
              class="flex items-center justify-between p-4 bg-gray-50 rounded-lg shadow-sm"
            >
              <!-- Task Radio Button and Text -->
              <div class="flex items-center space-x-4 flex-grow">
                <!-- Radio Button to Mark Task as Done -->
                <input
                  type="checkBox"
                  :checked="task.isDone"
                  @change="toggleDone(index)"
                  class="w-5 h-5 text-blue-600 border-2 border-gray-300 rounded-full focus:ring-blue-500 transition-all"
                />

                <!-- Task Text -->
                <div v-if="!task.isEditing" class="flex-grow">
                  <h3
                    class="text-lg font-semibold text-gray-800"
                    :class="{ 'line-through text-gray-400': task.isDone }"
                  >
                    {{ task.title }}
                  </h3>
                  <p
                    class="text-sm text-gray-600"
                    :class="{ 'line-through text-gray-400': task.isDone }"
                  >
                    {{ task.description }}
                  </p>
                      <span :class="getTaskTypeBadgeClass(task.type.id)">
                        {{ task.type.name }}
                      </span>
                </div>

                

                <!-- Edit Task Input -->
                <div v-else class="flex-grow">
                  <input
                    v-model="task.title"
                    type="text"
                    placeholder="Title"
                    class="w-full px-2 py-1 mb-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                  />
                  <textarea
                    v-model="task.description"
                    placeholder="Description"
                    class="w-full px-2 py-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                  ></textarea>
                  <select
                      :value="task.type.id"
                      @change="updateTaskType(index, $event)"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    >
                      <option value="" disabled>Select Task Type</option>
                      <option v-for="type in taskTypes" :key="type.id" :value="type.id">
                        {{ type.name }}
                      </option>
                    </select>
                </div>


                    
              </div>

              <!-- Actions -->
              <div class="flex space-x-2">
                <!-- Edit Button -->
                <button
                  @click="toggleEdit(index)"
                  class="text-blue-600 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all"
                >
                  <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                    ></path>
                  </svg>
                </button>

                <!-- Delete Button -->
                <button
                  @click="removeTask(index)"
                  class="text-red-600 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all"
                >
                  <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    ></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
      </div>
      <!-- Pagination Controls -->
    <div class="flex justify-center mt-6">
      <button
        @click="prevPage"
        :disabled="pagination.current_page === 1"
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all"
      >
        Previous
      </button>
      <span class="mx-4 text-gray-700">Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
      <button
        @click="nextPage"
        :disabled="pagination.current_page === pagination.last_page"
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all"
      >
        Next
      </button>
    </div>

      <!-- Logout Button -->
      <div class="text-center mt-6">
        <button
          @click="handleLogout"
          class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all"
        >
          Logout
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import auth from '@/auth';
import request from '@/request';

export default {
  name: 'UserDashboard',
  data() {
    return {
      user: null,
      newTask: {
        title: '',
        description: '',
        type: 0,
      },
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 5,
        searchQuery: '',
        selectedType: '',
      },
      loading: true,
      tasks: [],
      taskTypes: [], 
      searchQuery: '',
      filteredTasks: [],
      currentPage: 1,
      itemsPerPage: 5, // Number of tasks per page
    };
  },
  computed: {
    totalPages() {
      return this.pagination.last_page;
    },
    paginatedTasks() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredTasks.slice(start, end);
    },
  },
  async mounted() {
    // Fetch tasks when the component is mounted
    try {
      const response = await auth.getUser();
      this.user = response.data;
      await this.loadTasks(); 
    } catch {
      this.$router.push('/login');
    }
  },
  methods: {
    updateTaskType(index, event) {
      const selectedId = event.target.value; // Get the selected ID from the <select> element
      const selectedType = this.taskTypes.find(type => type.id == selectedId); // Find the corresponding type object
      if (selectedType) {
        // Update the type for the specific task using the index
        this.tasks[index].type = { ...selectedType };
      } else {
        this.tasks[index].type = { id: null, name: '' }; // Reset if no type is selected
      }
    },
    async addTask() {
      if (this.newTask.title.trim() === '') return;
      const payload = {
        title: this.newTask.title,
        description: this.newTask.description,
        type: this.newTask.type,
        isEditing: false,
        isDone: false,
      };
      request.addTask(payload);
      this.newTask.title = ''; 
      this.newTask.description = ''; 
      this.loadTasks();
    },
    toggleEdit(index) {
      this.tasks[index].isEditing = !this.tasks[index].isEditing;
      if (!this.tasks[index].isEditing) {

        const payload = {
          id: this.tasks[index].id,
          description: this.tasks[index].description,
          title: this.tasks[index].title,
          type: this.tasks[index].type,
          is_editing: true,
          is_done: this.tasks[index].isDone,
        };

        request.editTask(payload);
      }
      
    },
    saveTask(index) {
      this.tasks[index].isEditing = false;
      this.saveTasks();
    },
    removeTask(index) {
      request.taskRemove(this.tasks[index].id);
      this.tasks.splice(index, 1);
      this.loadTasks();
    },
    toggleDone(index) {
      this.tasks[index].isDone = !this.tasks[index].isDone;
      
      const payload = {
        id: this.tasks[index].id,
        isDone: this.tasks[index].isDone,
      };

      request.taskDone(payload);
    },
    async loadTasks() {
      const payload = {
          page: this.pagination.current_page,
          limit: this.pagination.per_page,
          search: this.pagination.searchQuery,
          type: this.pagination.selectedType,
        }

      const savedTasks = await request.fetchTasks(payload);
      if (savedTasks) {
        this.tasks = savedTasks.tasks;
        this.taskTypes = savedTasks.lookups.types, 
        this.pagination = savedTasks.pagination;
        this.loading = false; // Disable loading state
      }
    },
    saveTasks() {
      localStorage.setItem('tasks', JSON.stringify(this.tasks));
    },
    async handleLogout() {
      await auth.logout();
      localStorage.removeItem('token');
      this.$router.push('/login');
    },
    searchTasks() {
      
      this.pagination.current_page = 1; // Reset to the first page after search
      this.loadTasks(); // Reset pagination after search
    },
    getTaskTypeBadgeClass(type) {
      if (type === 0) {
        return "px-3 py-1 text-sm font-medium text-white bg-blue-500 rounded-full";
      } else if (type === 1) {
        return "px-3 py-1 text-sm font-medium text-white bg-green-500 rounded-full";
      } else if (type === 2) {
        return "px-3 py-1 text-sm font-medium text-white bg-red-500 rounded-full";
      }
      return "";
    },
    nextPage() {
      if (this.pagination.current_page < this.pagination.last_page) {
        this.pagination.current_page++;
        this.loadTasks();
      }
    },
    prevPage() {
      if (this.pagination.current_page > 1) {
        this.pagination.current_page--;
        this.loadTasks();
      }
    },
  },
};
</script>

<style scoped>
/* Add custom animations */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Apply animations to the dashboard content */
div.bg-white {
  animation: fadeIn 0.5s ease-in-out;
}

/* Hover effect for the dashboard container */
div.bg-white {
  transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

div.bg-white:hover {
  transform: scale(1.02);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

/* Custom CSS for line-through effect */
.line-through {
  text-decoration: line-through;
}
</style>