<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600">
      <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md transform transition-all duration-500 hover:scale-105">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Register</h2>
        <form @submit.prevent="handleRegister" class="space-y-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="name"
              type="text"
              id="name"
              placeholder="Enter your name"
              required
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
            />
          </div>
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
              v-model="email"
              type="email"
              id="email"
              placeholder="Enter your email"
              required
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
            />
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input
              v-model="password"
              type="password"
              id="password"
              placeholder="Enter your password"
              required
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
            />
          </div>
          <div>
            <label for="passwordConfirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input
              v-model="passwordConfirmation"
              type="password"
              id="passwordConfirmation"
              placeholder="Confirm your password"
              required
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
            />
          </div>
          <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all"
          >
            Register
          </button>
        </form>
        <p v-if="errorMessage" class="mt-4 text-center text-red-500">{{ errorMessage }}</p>
        <p v-if="successMessage" class="mt-4 text-center text-green-500">{{ successMessage }}</p>
        <p class="mt-6 text-center text-gray-600">
          Already have an account?
          <router-link to="/login" class="text-blue-600 hover:underline">Login here</router-link>.
        </p>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'UserRegister',
    data() {
      return {
        name: '',
        email: '',
        password: '',
        passwordConfirmation: '',
        errorMessage: '',
        successMessage: '',
      };
    },
    methods: {
      async handleRegister() {
        // Validate password confirmation
        if (this.password !== this.passwordConfirmation) {
          this.errorMessage = 'Passwords do not match';
          return;
        }
  
        try {
          // Call the register endpoint
          await axios.post('/register', {
            name: this.name,
            email: this.email,
            password: this.password,
            password_confirmation: this.passwordConfirmation,
          });
  
          // Handle successful registration
          this.successMessage = 'Registration successful! Redirecting to login...';
          this.errorMessage = '';
  
          // Redirect to login page after 2 seconds
          setTimeout(() => {
            this.$router.push('/login');
          }, 2000);
        } catch (error) {
          // Handle registration errors
          if (error.response && error.response.data.errors) {
            this.errorMessage = error.response.data.message || 'Registration failed';
          } else {
            this.errorMessage = 'An error occurred during registration';
          }
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
  
  /* Apply animations to the form */
  form {
    animation: fadeIn 0.5s ease-in-out;
  }
  
  /* Hover effect for the form container */
  div.bg-white {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
  }
  
  div.bg-white:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
  }
  </style>