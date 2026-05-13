<template>
  <MainLayout>
    <h1>Dashboard</h1>
    <button @click="logout">Logout</button>
    <button @click="counter.increment()">Increment(++)</button>
    <h2>{{ counter.count }}</h2>
    <button @click="counter.decrement()">Decrement(--)</button>
  </MainLayout>
</template>

<script setup>
import axios from "axios";
import MainLayout from "../layouts/MainLayout.vue";
import { useCounterStore } from "../stores/counter";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const router = useRouter();
const authStore = useAuthStore();
const counter = useCounterStore();
const logout = async () => {
  await axios.post(
    "http://localhost:8081/api/logout",
    {},
    {
      withCredentials: true,
    }
  );

  authStore.logout();
  router.push("/login");
};
</script>
