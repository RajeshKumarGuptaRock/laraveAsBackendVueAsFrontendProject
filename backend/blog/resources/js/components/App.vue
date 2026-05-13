<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const posts = ref([]);

onMounted(async () => {
    const response = await api.get('/posts');

    posts.value = response.data;
});
</script>

<template>
    <div>
      <router-view />
        <h1>Posts</h1>

        <div v-for="post in posts" :key="post.id">
            {{ post.title }}
        </div>
    </div>
</template>