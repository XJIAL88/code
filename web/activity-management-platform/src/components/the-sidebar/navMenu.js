/**
 *  创建icon函数
 * @param {function} create 创建函数
 * @param {Object} item 菜单单项数据
 */
function createIcon(create, item) {
    return create(
        'i', {
            class: {
                [item.icon]: true
            }
        },
        ''
    );
}

/**
 *  创建label函数
 * @param {function} create 创建函数
 * @param {Object} item 菜单单项数据
 */
function createLabel(create, item) {
    return create(
        'span', {
            slot: 'title'
        },
        item.label
    );
}

/**
 *  创建icon和label函数
 * @param {function} create 创建函数
 * @param {Object} item 菜单单项数据
 */
function createIconLabel(create, item) {
    if (item.icon) {
        return [createIcon(create, item), createLabel(create, item)];
    } else {
        return [createLabel(create, item)];
    }
}
/**
 * 控制页面是否展示菜单的状态
 */
const PERMISSION_TYPE = {
    show: 1,
    hide: 2
}

export default {
    name: 'header-menu',
    props: {
        menu: {
            type: Array,
            default: []
        },
        activeIndex: String
    },
    methods: {
        selectHandler(key, keyPath, item) {
            this.$emit('select', {
                key,
                keyPath,
                item
            });
        }
    },
    render(h) {
        const activeIndex = this.activeIndex.split(":")[0];

        const getChildrenContent = res => {
            return res.map(item => {

                const permission = item.permission ? {
                    name: 'permission',
                    value: item.permission
                } : {};

                return item.children ?
                    h(
                        'el-submenu', {
                            attrs: {
                                index: item.index,
                                route: item.route
                            },
                            directives: [{
                                ...permission
                            }]
                        },
                        [
                            h(
                                'template', {
                                    slot: 'title'
                                },
                                createIconLabel(h, item)
                            ),
                            ...getChildrenContent(item.children)
                        ]
                    ) :
                    h(
                        'el-menu-item', {
                            attrs: {
                                index: item.index,
                                route: item.route,
                                disabled: item.disabled
                            },
                            directives: [
                                permission
                            ]
                        },
                        createIconLabel(h, item)
                    );
            });
        };
        return h(
            'el-menu', {
                class: {
                    'self-elementui-menu': true
                },
                attrs: {
                    'default-active': activeIndex,
                    'background-color': '#264e81',
                    'text-color': "#fff"
                },
                on: {
                    select: this.selectHandler
                }
            },
            getChildrenContent(this.menu)
        );
    }
};
