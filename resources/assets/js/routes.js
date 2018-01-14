import VueRouter from 'vue-router'
import helper from './services/helper'
import store from './store'

let routes = [
    {
        path: '/',
        component: require('./layouts/default-page'),
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: require('./views/meal/index')
            },
            {
                path: '/meal',
                component: require('./views/meal/index')
            },
            {
                path: '/profile',
                component: require('./views/user/profile')
            },
            {
                path: '/test',
                component: require('./views/test')
            }
        ]
    },
    {
        path: '/',
        component: require('./layouts/default-page'),
        meta: {
            requiresAuth: true,
            requiresAdminOrManager: true
         },
        children: [
            {
                path: '/manage-users',
                component: require('./views/user/manage')
            }
        ]
    },
    {
        path: '/',
        component: require('./layouts/default-page'),
        meta: {
            requiresAuth: true,
            requiresAdmin: true
         },
        children: [
            {
                path: '/manage-meals',
                component: require('./views/meal/manage')
            }
        ]
    },
    {
        path: '/',
        component: require('./layouts/guest-page'),
        meta: {
            requiresGuest: true
        },
        children: [
            {
                path: '/login',
                component: require('./views/auth/login')
            },
            {
                path: '/register',
                component: require('./views/auth/register')
            }
        ]
    },
    {
        path: '*',
        component : require('./layouts/error-page'),
        children: [
            {
                path: '*',
                component: require('./views/errors/page-not-found')
            }
        ]
    }
];

const router = new VueRouter({
	routes,
    linkActiveClass: 'active',
    mode: 'history'
});

router.beforeEach((to, from, next) => {

    if (to.matched.some(m => m.meta.requiresAuth)) {
        return helper.check().then(response => {
            if (!response) {
                return next({ path : '/login'})
            } else {
                if (to.matched.some(m => m.meta.requiresAdminOrManager)) {
                    if (store.getters.getAuthUserRole != 2 && store.getters.getAuthUserRole != 3) {
                        return next({ path : '/404.html'})
                    }
                } else if (to.matched.some(m => m.meta.requiresAdmin)) {
                    if (store.getters.getAuthUserRole != 3) {
                        return next({ path : '/404.html'})
                    }
                }
            }

            return next()
        })
    }


    if (to.matched.some(m => m.meta.requiresGuest)) {
        return helper.check().then(response => {
            if(response) {
                return next({ path : '/'})
            }

            return next()
        })
    }

    return next()
});

export default router;
