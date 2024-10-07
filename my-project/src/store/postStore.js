import axios from 'axios';

const postStore = {
  namespaced: true,
  state: {
    posts: [],
  },

  mutations: {
    ADD_POST(state, post) {
      state.posts.push(post);
    },
    SET_POSTS(state, posts) {
      state.posts = posts;
    },
  },

  actions: {
    async addPost({ commit }, postData) {
      try {
        const response = await axios.post('http://localhost:8001/api.php', postData, {
          headers: {
            'Content-Type': 'application/json', // Ensure JSON format for sending data
          },
        });
        commit('ADD_POST', { ...postData, id: response.data.id }); // Adjust based on your API response format
        return response.data; // Return the response for further handling
      } catch (error) {
        console.error('Error adding post:', error);
        throw error; // Rethrow error for further handling in the component
      }
    },

    async fetchPosts({ commit }) {
      try {
        const response = await axios.get('http://localhost:8001/api.php');
        commit('SET_POSTS', response.data); // Populate the posts array
      } catch (error) {
        console.error('Error fetching posts:', error);
      }
    },
  },

  getters: {
    allPosts(state) {
      return state.posts; 
    },
  },
};

export default postStore;
