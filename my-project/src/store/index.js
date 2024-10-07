import { createStore } from 'vuex';
import postStore from './postStore'; // Import the postStore module

const store = createStore({
  modules: {
    posts: postStore, // Register the postStore module
  },
});

export default store;
