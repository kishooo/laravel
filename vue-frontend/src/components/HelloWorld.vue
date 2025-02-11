<template>
  <div>
    <h1>{{ message }}</h1>
  </div>
</template>

<script>
export default {
  data() {
    return {
      message: 'Loading...', // Default message
    };
  },
  async created() {
    try {
      const response = await this.$axios.get('/test'); // Calls `http://localhost:8000/api/test`
      this.message = response.data.message; // Assuming API response: `{ "message": "Hello from Laravel API" }`
    } catch (error) {
      console.error('Error fetching data:', error);
      this.message = 'Failed to load data';
    }
  },
};
</script>
