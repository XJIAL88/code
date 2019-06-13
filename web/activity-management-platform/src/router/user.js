export default [{
    path: '/user/list',
    name: 'userList',
    meta: {
        nav: ['user', 'user-list'],
        bread: []
    },
    component: () => import('@/views/user/list')
}, ];
