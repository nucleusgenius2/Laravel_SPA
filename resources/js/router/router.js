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
            component: () => import("../views/SingleNews.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/post-list/:total/:page",
            name: "ListPost",
            component: () => import("../views/NewsPage.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/registration",
            name: "Registration",
            component: () => import("../views/Registration.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/login",
            name: "Login",
            component: () => import("../views/Login.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/profile",
            name: "Profile",
            component: () => import("../views/profile.vue"),
            meta: {
                layout : "mainLayout"
            }
        },
        {
            path: "/admin/:edit/:id/:page",
            name: "Admin",
            component: () => import("../views/Admin.vue"),
            meta: {
                layout : "admin"
            }
        },

    ],
});

// protect router
router.beforeEach( async (to, from, next) => {
    if ( to.name === 'Admin' || to.name === 'Profile' ) {
        if ( localStorage.getItem("token") !== null ) {

            let response = await authRequest('/api/authorization', 'get');

            if (to.name === 'Admin') {
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
