import axios from 'axios';

export default {
  async fetchTasks(payload) {
    try {
      const response = await axios.post('/tasks/list', {
        payload
      });
      return response.data;
    } catch (error) {
      console.error('Error fetching tasks:', error);
    }
  },

  async addTask(payload) {
    try {
      console.log("type",payload.type)
      const response = await axios.put('/tasks', {
        title: payload.title,
        description: payload.description,
        type: payload.type,
      });
      return response;
    } catch (error) {
      console.error('Error fetching tasks:', error);
    }
  },

  async editTask(payload) {
    try {
      const response = await axios.post('/tasks', {
        id: payload.id,
        title: payload.title,
        description: payload.description,
        is_done: payload.is_done,
        type: payload.type,
        is_editing:true,
      });
      return response;
    } catch (error) {
      console.error('Error fetching tasks:', error);
    }
  },

  async taskDone(payload) {
    try {
      const response = await axios.post('/tasks', {
        id: payload.id,
        is_done: payload.is_done,
        is_editing:false,
      });
      return response;
    } catch (error) {
      console.error('Error fetching tasks:', error);
    }
  },

  async taskRemove(id) {
    try {
      const response = await axios.delete(`/tasks/${id}`);
      return response;
    } catch (error) {
      console.error('Error fetching tasks:', error);
    }
  },
};
