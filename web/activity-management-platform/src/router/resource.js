export default [{
    path: '/resource/list',
    name: 'resourceList',
    meta: {
        nav: ['resource'],
        bread: []
    },
    component: () => import('@/views/resource/list')
}, {
    path: '/resource/prize-list',
    name: 'prizeList',
    meta: {
        nav: ['resource'],
        bread: []
    },
    component: () => import('@/views/resource/prize-list')
}, {
    path: '/resource/prize/new',
    name: 'prizeNew',
    meta: {
        nav: ['resource:/resource/prize-list'],
        bread: ['新建奖品']
    },
    component: () => import('@/views/resource/prize')
}, {
    path: '/resource/prize/edit',
    name: 'prizeEdit',
    meta: {
        nav: ['resource:/resource/prize-list'],
        bread: ['修改奖品']
    },
    component: () => import('@/views/resource/prize')
}]
