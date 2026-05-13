import { defineStore } from 'pinia';

import axios from 'axios';

export const useAuthStore = defineStore('auth', {

    state: () => ({
        user: null,
    }),

    actions: {

        async fetchUser() {

            try {

                const response = await axios.get(
                    'http://localhost:8081/api/user',
                    {
                        withCredentials: true
                    }
                );

                this.user = response.data;

            } catch (error) {

                this.user = null;

            }

        },

        setUser(user) {

            this.user = user;

        },

        logout() {

            this.user = null;

        }

    }

});