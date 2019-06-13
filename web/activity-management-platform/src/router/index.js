import Vue from 'vue'
import Router from 'vue-router'
import {
    handleNavBreadDisplay,
    getUserId
} from '@/utils';
import store from '@/store';
import {
    API_CONFIG
} from '@/api/config'

import home from '@/views/home'
import user from './user.js'
import activity from './activity.js'
import resource from './resource.js'

Vue.use(Router);

const router = new Router({
    routes: [{
        path: '/',
        name: 'home',
        component: home,
        children: [
            ...user,
            ...resource,
            ...activity
        ],
    }, {
        path: '/login',
        name: 'login',
        component: () => import('@/views/login')
    }]

});
router.beforeEach((to, from, next) => {
    let isLogin = getUserId();
    if (!isLogin) {
        window.location.href = `${API_CONFIG.baseURL}oauthWithPlatform?url=` + encodeURIComponent(window.location.href);
    } else {
        handleNavBreadDisplay(to, store);
        next();
    }

});

router.afterEach((to, from) => {

    //处理通过路由进入菜单的方式
    const nav = router.app.$store.state.navData;
    let item = {};
    routesTrack(nav, null, to.name, item);
    // console.log(match)
    if (item._path) {
        router.app.$store.commit('setActiveIndex', to.name);
        router.app.$store.commit('setBreadData', item._path);
    }

});

function routesTrack(navs, parent, name, match) {

    for (let i = 0; i < navs.length; i += 1) {
        const nav = navs[i];
        nav._parent = parent;
        if (parent === null) {
            nav._path = [nav.index];

        } else {

            const _path = [...parent._path];
            _path.push(nav.index);
            nav._path = _path;
        }
        if (name === nav.index) {

            match = Object.assign(match, nav);
            break;

        } else if (nav.children) {
            routesTrack(nav.children, nav, name, match);
        }

    }

}

export default router;
