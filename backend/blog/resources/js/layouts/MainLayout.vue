<template>
    <div>

        <nav style="padding:20px; background:#eee">
            <router-link to="/">Dashboard</router-link>

            |

            <router-link to="/posts">Posts</router-link>
            |

            <router-link to="/login">Login</router-link>
        </nav>

        <div style="padding:20px">
            <slot />
        </div>

    </div>
</template>