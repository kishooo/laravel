import axios from 'axios';

export default {
  async login(credentials) {
    await axios.get('/sanctum/csrf-cookie'); // CSRF protection
    const response = await axios.post('/login', credentials);
    const token = response.data.token; // Assuming the token is returned in the response
    localStorage.setItem('token', token); // Store the token in local storage
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    return response;
  },

  async logout() {
    return axios.post('/logout');
  },

  async getUser() {
    return axios.get('/user');
  },

  isLoggedIn() {
    return !!localStorage.getItem('token'); // Check if a token exists
  },
};
