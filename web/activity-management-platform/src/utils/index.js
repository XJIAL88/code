import {
    user
} from '@/api/config';
export function formatTimeNumber(number) {
    return parseInt(number) < 10 ? '0' + number : number;
}

export const cookie = {
    set: function set(name, value, expHour, domain, path) {
        document.cookie =
            name +
            '=' +
            encodeURIComponent(value == undefined ? '' : value) +
            (expHour ?
                '; expires=' +
                new Date(
                    new Date().getTime() + (expHour - 0) * 3600000
                ).toUTCString() :
                '') +
            '; domain=' +
            (domain ? domain : document.domain) +
            '; path=' +
            (path ? path : '/');
        return cookie;
    },
    get: function get(name) {
        return document.cookie.match(
                new RegExp('(^| )' + name + '=([^;]*)(;|$)')
            ) == null ?
            null :
            decodeURIComponent(RegExp.$2);
    },
    remove: function remove(name) {
        if (this.get(name) != null) this.set(name, null, -1);
        return cookie;
    },
};

export function saveUserInfo({
    id,
    token,
    name,
    power
}) {
    cookie.set('__ADMIN_PLATFORM_ID__', id, 24 * 7);
    cookie.set('__ADMIN_PLATFORM_TOKEN__', token, 24 * 7);
    cookie.set('__ADMIN_PLATFORM_NAME__', name, 24 * 7);
    cookie.set('__ADMIN_PLATFORM_POWER__', power, 24 * 7);
}

/**
 * 删除用户身份
 * @private
 */
export function removeUserInfo() {
    cookie.remove('__ADMIN_PLATFORM_ID__');
    cookie.remove('__ADMIN_PLATFORM_TOKEN__');
    cookie.remove('__ADMIN_PLATFORM_NAME__');
    // cookie.remove('__ADMIN_PLATFORM_POWER__');
}

/**
 * 获取用户ID
 * @returns {Number}
 */
export function getUserId() {
    return parseInt(cookie.get('__ADMIN_PLATFORM_ID__') || user.id);
}

/**
 * 获取用户名称
 * @returns {*}
 */
export function getUserName() {
    return cookie.get('__ADMIN_PLATFORM_NAME__') || '';
}

/**
 * 获取用户Token
 * @returns {*}
 */
export function getUserToken() {
    return cookie.get('__ADMIN_PLATFORM_TOKEN__') || user.token;
}

/**
 * 用户权限
 * @param {} power
 */
export function setUserPower(power) {
    cookie.set('__ADMIN_PLATFORM_POWER__', power, 24 * 7);
}

/**
 * 获取用户权限
 * @returns {Number}
 */
export function getUserPower() {
    return cookie.get('__ADMIN_PLATFORM_POWER__');
}

/**
 * 格式化用户身份参数
 * @param params
 * @private
 */
export function formatUserParams(params) {
    const result = params || {};
    result.adminId = parseInt(getUserId()) || 0;
    result.adminToken = getUserToken();
    return result;
}

/**
 * 获取当前年月
 * @returns {string}
 */
export function getCurrentYearMonth() {
    return (
        new Date().getFullYear().toString() +
        formatTimeNumber(new Date().getMonth() + 1).toString()
    );
}

export function isMobile(value) {
    const reg = /^((1[3-8][0-9])+\d{8})$/;
    return reg.test(value);
}

//根据路由to的属性设置面包屑
export const handleNavBreadDisplay = (to, store) => {
    const metaNavArr = to.meta.nav || [];
    const metaBreadArr = [];

    if (to.meta.bread) {
        to.meta.bread.forEach(item => {
            const item_split = item.split(':');
            let label = item_split[0];
            let path = item_split[1] || '';

            if (/^{\w+}$/.test(label)) {
                const key = label.substr(1, label.length - 2);
                if (to.query.hasOwnProperty(key)) {
                    label = to.query[key];
                } else {
                    const breads = sessionStorageGet('breads');
                    if (breads && breads.hasOwnProperty(key)) {
                        label = breads[key];
                    }
                }
            }

            if (path) {
                const matchs = path.match(/{\w+}/g);

                if (matchs) {
                    //首先从query中获取,
                    matchs.forEach(path_item => {
                        const key = path_item.substr(1, path_item.length - 2);
                        if (to.query.hasOwnProperty(key)) {
                            path = path.replace(path_item, to.query[key]);
                        } else {
                            const breads = sessionStorageGet('breads');
                            if (breads && breads.hasOwnProperty(key)) {
                                path = path.replace(path_item, breads[key]);
                            }
                        }
                    });
                }
            }

            metaBreadArr.push({
                label: label,
                path: path,
            });
        });
    }

    const tMetaBreadArr = [].concat(metaBreadArr); // 防止数组的引用修改原数组
    // START 如果有projectName标记 则替换为项目名
    // const index = tMetaBreadArr.indexOf('projectName');
    // if (index !== -1 && store.state.project.detail.projectName) {
    //   console.log('projectName = ' + store.state.project.detail.projectName);
    //   tMetaBreadArr[index] = { label: store.state.project.detail.projectName + '项目' };
    // } // END
    const navLabelArr = handleBreadData(store.state.navData, metaNavArr, to);

    store.commit('setBread', {
        nav: navLabelArr,
        bread: tMetaBreadArr,
    });
    if (navLabelArr.length > 0) {
        // 设置左边导航的当前active
        const key = metaNavArr[metaNavArr.length - 1] || '';
        store.commit('setActiveIndex', key);
        store.commit('setModuleIndex', metaNavArr[0].split(':')[0]);
    }
};

// 顶部面包屑数据处理
export const handleBreadData = (data, keyPath, route) => {
    let arr = [];
    let target = data;

    keyPath.forEach(item => {
        const item_split = item.split(':');
        const t = target.filter(menuItem => item_split[0] === menuItem.index);
        if (t.length > 0) {
            //
            if (item_split[1]) {
                const matchs = item_split[1].match(/{\w+}/g);
                // console.log("match", matchs)
                if (matchs) {
                    //首先从query中获取,
                    matchs.forEach(item => {
                        const key = item.substr(1, item.length - 2);
                        if (route.query.hasOwnProperty(key)) {
                            item_split[1] = item_split[1].replace(item, route.query[key]);
                        } else {
                            const breads = sessionStorageGet('breads');
                            if (breads && breads.hasOwnProperty(key)) {
                                item_split[1] = item_split[1].replace(item, breads[key]);
                            }
                        }
                    });
                }
            }

            //

            const breadItem = {
                label: t[0].label,
                path: item_split[1] || '',
            };
            arr.push(breadItem);
            target = t[0].children || [];
        }
    });
    return arr;
};

export const handleExpandBreadData = (previous, extra) => {
    let arr = [];
    if (extra) {
        arr = previous.concat(extra);
    }
    return arr;
};

// sessionStorage
export const sessionStorageSet = (key, value) => {
    window.sessionStorage.setItem(key, JSON.stringify(value));
};
export const sessionStorageGet = key =>
    JSON.parse(window.sessionStorage.getItem(key));
export const sessionStorageRemove = key => {
    window.sessionStorage.removeItem(key);
}

// localStorage
export const localStorageSet = (key, value) => {
    window.localStorage.setItem(key, JSON.stringify(value));
};
export const localStorageGet = key =>
    JSON.parse(window.localStorage.getItem(key));

export function getFileSuffix(filename) {
    if (filename) {
        return filename.substr(filename.lastIndexOf('.') + 1);
    } else {
        return '';
    }
}

export function classifyGetParentList(classifyUuid, classifyTree) {
    let resultPath = [];
    classifyGetParentListCallback(classifyUuid, classifyTree, function (result) {
        resultPath = result;
    });
    return resultPath;
}

export function classifyGetParentListCallback(
    classifyUuid,
    classifyTree,
    callback
) {
    let resultPath = [];
    try {
        function getClassify(classify) {
            resultPath.push(classify.id);
            if (classifyUuid === classify.id) {
                throw 'OK';
            }
            if (classify.son && classify.son.length > 0) {
                for (let i = 0; i < classify.son.length; i++) {
                    getClassify(classify.son[i]);
                }
                resultPath.pop();
            } else {
                resultPath.pop();
            }
        }

        function getClassifyList(classifyList) {
            for (let a in classifyList) {
                if (classifyList.hasOwnProperty(a)) {
                    getClassify(classifyList[a]);
                }
            }
        }

        getClassifyList(classifyTree);
    } catch (e) {
        callback(resultPath);
    }
}
