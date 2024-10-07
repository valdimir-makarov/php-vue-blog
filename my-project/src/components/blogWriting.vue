<template>
  <div>
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-6 text-center">Publish Your Blog Post</h2>
      <form @submit.prevent="setData">
        <div class="mb-6">
          <label for="title" class="block text-gray-700 font-semibold mb-2">Title</label>
          <input 
            type="text" 
            id="title" 
            v-model="title" 
            required 
            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-200" 
          />
        </div>
        <div class="mb-6">
          <label for="content" class="block text-gray-700 font-semibold mb-2">Content</label>
          <textarea 
            id="content" 
            v-model="content" 
            required 
            rows="6" 
            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-200" 
          ></textarea>
        </div>
        <div class="mb-6">
          <label for="user_id" class="block text-gray-700 font-semibold mb-2">User ID</label>
          <input 
            type="text" 
            id="user_id" 
            v-model="user_id" 
            required 
            class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-200" 
          />
        </div>
        <div class="flex justify-end">
          <button 
            type="submit" 
            class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
            Publish
          </button>
        </div>
      </form>
    </div>

    <div class="mt-8">
      <h2 class="text-2xl font-bold mb-4 text-center">All Posts</h2>
      <ul>
        <li v-for="post in posts" :key="post.id" class="mb-4 p-4 border border-gray-300 rounded-lg">
          <h3 class="text-xl font-semibold">{{ post.title }}</h3>
          <p>{{ post.content }}</p>
          <p class="text-gray-500">User ID: {{ post.user_id }}</p>
          <p class="text-gray-400">{{ post.created_at }}</p> <!-- Optionally format this date -->
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
  data() {
    return {
      title: '',
      content: '',
      user_id: '',
    };
  },
  computed: {
    ...mapGetters('posts', ['allPosts']), // Use 'posts' namespace for getters
    posts() {
      return this.allPosts;
    },
  },
  methods: {
    ...mapActions('posts', ['addPost', 'fetchPosts']), // Use 'posts' namespace for actions
    async setData() {
      const postData = {
        title: this.title,
        content: this.content,
        user_id: this.user_id,
      };

      try {
        await this.addPost(postData);
        await this.fetchPosts();
        this.title = '';
        this.content = '';
        this.user_id = '';
      } catch (error) {
        console.error('Failed to publish post:', error);
      }
    },
  },
  mounted() {
    this.fetchPosts();
  },
};
</script>
