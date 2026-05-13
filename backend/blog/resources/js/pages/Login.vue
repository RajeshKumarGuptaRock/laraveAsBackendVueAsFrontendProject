<template>
  <div>
    <h1>Login</h1>

    <form @submit.prevent="login">
      <div>
        <input v-model="form.email" type="email" placeholder="Email" />
      </div>

      <div>
        <input v-model="form.password" type="password" placeholder="Password" />
      </div>

      <button>Login</button>
    </form>
  </div>
</template>

<script setup>
import { reactive } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const router = useRouter();
const authStore = useAuthStore();

const form = reactive({
  email: "",
  password: "",
});

const login = async () => {
  try {
    await axios.get("http://localhost:8081/sanctum/csrf-cookie", {
      withCredentials: true,
    });

    const response = await axios.post("http://localhost:8081/api/login", form, {
      withCredentials: true,
    });

    authStore.setUser(response.data.user);

    router.push("/dashboard");
  } catch (error) {
    console.log(error.response.data);
  }
};
</script>
