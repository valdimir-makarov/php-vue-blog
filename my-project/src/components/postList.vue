<template>
    <div class="max-w-2xl mx-auto">
      <h2 class="text-2xl font-bold mb-6">Posts</h2>
      <div v-if="posts.length === 0" class="text-gray-500">No posts available.</div>
      <ul>
        <li v-for="post in posts" :key="post.title" class="mb-4 p-4 border rounded shadow">
          <h3 class="text-xl font-semibold">{{ post.title }}</h3>
          <p class="mt-2">{{ post.content }}</p>
          <img v-if="post.image" :src="URL.createObjectURL(post.image)" alt="Post image" class="mt-2 w-full h-auto rounded" />
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import { mapGetters } from 'vuex';
  
  export default {
    computed: {
      ...mapGetters(['allPosts']),
      posts() {
        return this.allPosts;
      },
    },
  };
  </script>
  