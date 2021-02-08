import Vue from "vue";
import VueRouter from "vue-router";
// Pages
import Login from "./components/auth/Login";
import Logout from './components/auth/Logout'

import NotFound from './views/NotFound'
import Dashboard from './views/Dashboard'

import IndexMovies from "./views/movies/IndexMovies";
import CreateMovie from "./views/movies/CreateMovie";
import AssignMovieTurn from "./views/movies/AssignMovieTurn";

import IndexTurns from "./views/turns/IndexTurns";
import CreateTurn from "./views/turns/CreateTurn";

import Index from "./views/dashboard/Index";

Vue.use(VueRouter);

const routesWithPrefix = (prefix, routes) => {
    return routes.map(route => {
        route.path = `${prefix}${route.path}`

        return route
    })
}

// Routes
export default new VueRouter({
    mode: 'history',
    linkActiveClass: 'is-active',
    routes: [
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                requiresOffAuth: true,
            }
        },
        {
            path: '/logout',
            name: 'logout',
            component: Logout,
            meta: {
                requiresAuth: true,
            }
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: Dashboard,
            meta: {
                requiresAuth: true,
            }
        },
        {
            path: '/dashboard/index',
            name: 'dashboard.index',
            component: Index,
            meta: {
                requiresAuth: true,
            }
        },
        ...routesWithPrefix('/movies', [
            {
                path: '/',
                name: 'movies',
                component: IndexMovies,
                meta: {
                    requiresAuth: true,
                }
            },
            {
                path: '/create',
                name: 'movies.create',
                component: CreateMovie,
                meta: {
                    requiresAuth: true,
                }
            },
            {
                path: '/:id/edit',
                name: 'movies.edit',
                component: CreateMovie,
                meta: {
                    requiresAuth: true,
                }
            },
            {
                path: '/:id/assign-turn',
                name: 'movies.assign',
                component: AssignMovieTurn,
                meta: {
                    requiresAuth: true,
                }
            },
        ]),
        ...routesWithPrefix('/turns', [
            {
                path: '/',
                name: 'turns',
                component: IndexTurns,
                meta: {
                    requiresAuth: true,
                }
            },
            {
                path: '/create',
                name: 'turns.create',
                component: CreateTurn,
                meta: {
                    requiresAuth: true,
                }
            },
            {
                path: '/:id/edit',
                name: 'turns.edit',
                component: CreateTurn,
                meta: {
                    requiresAuth: true,
                }
            },
        ]),
        {
            path: '/404',
            name: '404',
            component: NotFound,
        },
        {
            path: '/',
            redirect: { name: 'login' },
        },
        {
            path: '*',
            redirect: { name: '404' },
        },
    ],
});
