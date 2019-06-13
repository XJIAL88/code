export default [{
        path: '/activity/list',
        name: 'activityList',
        meta: {
            nav: ['activity', 'activity-list'],
            bread: []
        },
        component: () => import('@/views/activity/list')
    }, {
        path: '/activity/new',
        name: 'activityNew',
        meta: {
            nav: ['activity', 'activity-list:/activity/list'],
            bread: ['新建活动']
        },
        component: () => import('@/views/activity/edit')
    }, {
        path: '/activity/edit',
        name: 'activityEdit',
        meta: {
            nav: ['activity', 'activity-list:/activity/list'],
            bread: ['编辑活动']
        },
        component: () => import('@/views/activity/edit')
    }, {
        path: '/activity/approval-list',
        name: 'activityApprovalList',
        meta: {
            nav: ['activity', 'activity-list:/activity/list'],
            bread: ['审批历史']
        },
        component: () => import('@/views/activity/approval-list')
    },
    {
        path: '/activity/draw-list',
        name: 'activityDrawList',
        meta: {
            nav: ['activity', 'activity-list:/activity/list'],
            bread: ['抽奖历史']
        },
        component: () => import('@/views/activity/draw-list')
    },
    {
        path: '/activity/prize-setting',
        name: 'activityPrizeSetting',
        meta: {
            nav: ['activity', 'activity-list:/activity/list'],
            bread: ['奖品设置']
        },
        component: () => import('@/views/activity/prize-setting')
    },
    {
        path: '/activity/detail',
        name: 'activityDetail',
        meta: {
            nav: ['activity', 'activity-list:/activity/list'],
            bread: ['活动详情']
        },
        component: () => import('@/views/activity/detail')
    }
];
