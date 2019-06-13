import Vue from 'vue';
import Vuex from 'vuex';
import {
    sessionStorageSet,
    sessionStorageGet,
    localStorageSet,
    localStorageGet,
    handleBreadData,
    handleExpandBreadData,
} from '@/utils';
import api from '@/api';

Vue.use(Vuex);
const store = new Vuex.Store({
    state: {
        navData: [
            // {
            //     label: '用户管理',
            //     icon: '',
            //     index: 'user',
            //     children: [{
            //         label: '用户列表',
            //         index: 'user-list',
            //         route: {
            //             path: '/user/list',
            //         },
            //     }, ],
            // },
            {
                label: '资源管理',
                icon: '',
                index: 'resource',
                route: {
                    path: '/resource/prize-list',
                }
            },
            {
                label: '活动管理',
                icon: '',
                index: 'activity',
                children: [{
                    label: '活动列表',
                    index: 'activity-list',
                    route: {
                        path: '/activity/list',
                    },
                }, ],
            }
        ],
        activeIndex: '',
        moduleIndex: '',
        breadData: [],
        expandBreadData: sessionStorageGet('bread_data') || [],
        permission: [],
    },
    getters: {},
    mutations: {
        setActiveIndex(state, index) {
            state.activeIndex = index;
            sessionStorageSet('active_index', index);
        },
        setModuleIndex(state, index) {
            state.moduleIndex = index;
            sessionStorageSet('module_index', index);
        },
        setBreadData(state, keyPath, ...extraPath) {
            // console.log(state, keyPath, extraPath)
            state.breadData = handleBreadData(state.navData, keyPath);
            state.expandBreadData = handleExpandBreadData(
                state.breadData,
                extraPath
            );
            sessionStorageSet('bread_data', state.breadData);
        },
        // 设置导航标签
        setBread(state, totalBreadObj) {
            if (totalBreadObj.nav.length > 0) {
                // 如果手动设置了meta.nav 则覆盖state.breadData
                state.breadData = totalBreadObj.nav;
                sessionStorageSet('bread_data', state.breadData);
            }
            state.expandBreadData = state.breadData.concat(totalBreadObj.bread);
            sessionStorageSet('expand_bread_data', state.expandBreadData);
        },
        update_customer_count(state, data) {
            state.customerCount = data;
        },
    },
    actions: {
        update_customer_count({
            commit
        }) {
            api
                .post(
                    'customerGetCount', {}, {
                        loading: false,
                    }
                )
                .then(res => {
                    commit('update_customer_count', res.data);
                });
        },
    },
});
export default store;
