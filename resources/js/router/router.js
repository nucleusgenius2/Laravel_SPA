import { createRouter, createWebHistory } from "vue-router";
import {authRequest} from "@/api.js";

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
            path: "/post/:id",
            name: "SinglePost",
            component: () => import("@/views/SinglePost.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/post-list/:page",
            name: "ListPost",
            component: () => import("@/views/ListPost.vue"),
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
            path: "/profile",
            name: "Profile",
            component: () => import("@/views/Profile.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        /*
        {
            //path: "/admin/:edit/:page",
            path: "/admin/",
            name: "admin",
            component: () => import("@/views/admin.vue"),
            meta: {
                layout : "admin"
            }
        },
        */
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
