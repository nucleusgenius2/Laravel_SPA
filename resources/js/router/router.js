import { createRouter, createWebHistory } from "vue-router";
import {authRequest} from "@/api.ts";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "Home",
            component: () => import("@/views/Home.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/posts/:id",
            name: "SinglePost",
            component: () => import("@/views/SinglePost.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/post-list/:page",
            name: "ListPost",
            component: () => import("@/views/PostsList.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/registration",
            name: "Registration",
            component: () => import("@/views/Registration.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/login",
            name: "Login",
            component: () => import("@/views/Login.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/reset-email",
            name: "PasswordResetEmail",
            component: () => import("@/views/ResetPasswordEmail.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/password/reset/:token",
            name: "PasswordReset",
            component: () => import("@/views/ResetPassword.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/profile",
            name: "Profile",
            component: () => import("@/views/Profile.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/question",
            name: "Question",
            component: () => import("@/views/Qestion.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/mods_l",
            name: "Mods",
            component: () => import("@/views/Mods.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/maps_l",
            name: "Maps",
            component: () => import("@/views/Maps.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/admin",
            name: "admin",
            component: () => import("@/views/admin/Admin.vue"),
            children: [
                {
                    path: '',
                    component: () => import("@/views/admin/AdminDashboard.vue"),
                },
                {
                    path: 'posts',
                    component: () => import("@/views/admin/AdminPosts.vue"),
                },
                {
                    path: 'posts/:id',
                    component: () => import("@/views/admin/AdminPostsSingle.vue"),
                },
                {
                    path: 'users',
                    component: () => import("@/views/admin/AdminUsers.vue"),
                },
                {
                    path: 'users/:id',
                    component: () => import("@/views/admin/AdminUsersSingle.vue"),
                },
                {
                    path: 'maps',
                    component: () => import("@/views/admin/AdminMaps.vue"),
                },
                {
                    path: 'mods',
                    component: () => import("@/views/admin/AdminMods.vue"),
                },
            ]
        },

        //page not found
        {
            path: "/404",
            name: "404",
            component: () => import("@/views/Page404.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: '/:pathMatch(.*)*',
            component: () => import("@/views/Page404.vue"),
            meta: {
                layout : "mainLayout"
            }
        },

    ],
});



// protect router
router.beforeEach( async (to, from, next) => {
    if ( to.name === 'admin' || to.name === 'Profile' ) {
        if ( localStorage.getItem("token") !== null ) {

            let response = await authRequest('/api/authorization', 'get');

            if (to.name === 'admin') {
                if (response.data.permission === 'admin') {
                    next()
                } else {
                    next({name: 'Login'})
                }
            }

            if (to.name === 'Profile') {
                if (response.data.permission === 'user' || response.data.permission === 'admin') {
                    next()
                } else {
                    next({name: 'Login'})
                }
            }
        }
        else {
            next({name: 'Login'})
        }
    }
    else {
        next();
    }
})

export default router;
