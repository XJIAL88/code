import axios from 'axios';
import {
    MessageBox,
    Loading
} from 'element-ui';
import {
    API_CONFIG
} from './config';
import {
    formatUserParams,
    removeUserInfo
} from '../utils/index';

let load;
const showLoading = () => {
    return Loading.service({
        lock: true,
        text: '加载中',
        background: 'rgba(0, 0, 0, 0.7)',
    });
};
const hideLoading = loading => {
    loading.close();
};
const service = axios.create({
    timeout: API_CONFIG.timeout, // 请求超时时间
    withCredentials: API_CONFIG.withCredentials, // 携带cookie
});
let pending = {};
const CANCEL_REQUEST = 'CANCEL_REQUEST';
let cancelToken = axios.CancelToken;
let cancelRequest = config => {
    config.cancelToken = new cancelToken(c => {
        c(CANCEL_REQUEST);
    });
};
service.interceptors.request.use(
    config => {
        if (config.method === 'post') {
            let params = {
                ...config.data,
            };
            if (params._cancelSameRequest) {
                if (pending[config.url]) {
                    load && hideLoading(load);
                    cancelRequest(config);
                } else {
                    pending[config.url] = true;
                }
            }
        } else {
            config.headers['Access-Control-Allow-Origin'] = "*";
        }
        return config;
    },
    error => {
        return Promise.reject(error);
    }
);

service.interceptors.response.use(
    response => {
        if (response.config.data) {
            const params = JSON.parse(response.config.data);
            if (params._cancelSameRequest) {
                setTimeout(() => {
                    pending[response.config.url] = false;
                }, params._cancelSameRequestTimeout);
            }
        }
        return response;
    },
    error => {
        pending[error.config.url] = false;
        if (error.response) {
            if (
                error.response.status === 404 ||
                error.response.status === 500 ||
                error.response.status === 504
            ) {
                MessageBox.alert('服务器错误' + `(${error.response.status})`, {
                    type: 'error',
                });
            }
        } else {
            if (error.message !== CANCEL_REQUEST) {
                MessageBox.alert(error.message + `(${error.code})`, {
                    type: 'error',
                });
            }
        }
        return Promise.reject(error);
    }
);
/*
    method: post/get
    url: API 地址
    params: API参数
    options:
        baseURL:API地址前缀
        loading:是否加载 loading 样式
        catchCode:catchCode 是否捕获后端错误码，某些情况下需捕获处理
        cancelSameRequest: 是否取消重复请求
        cancelSameRequestTimeout: 取消请求限制时间
 */
const httpRequest = function (
    method,
    url,
    params, {
        baseURL = API_CONFIG.baseURL,
        loading = true,
        catchCode = false,
        cancelSameRequest = false,
        cancelSameRequestTimeout = 1000,
    }
) {
    const completeUrl = baseURL + url;
    params = formatUserParams(params);
    params._cancelSameRequest = cancelSameRequest;
    params._cancelSameRequestTimeout = cancelSameRequestTimeout;
    if (loading) load = showLoading();
    return new Promise((resolve, reject) => {
        service
            [method](completeUrl, params)
            .then(res => {
                if (loading) hideLoading(load);
                if (catchCode) {
                    resolve(res.data);
                } else {
                    if (res.data.code === 0) {
                        // 接口正常
                        resolve(res.data);
                    } else {
                        handleResponse.checkApiCode(res);
                        reject(res.data);
                    }
                }
            })
            .catch(error => {
                if (loading) hideLoading(load);
                reject(error);
                if (
                    error.code === 'ECONNABORTED' &&
                    error.message.indexOf('timeout') !== -1
                ) {
                    MessageBox.alert('网络超时', {
                        type: 'error',
                    });
                }
            });
    });
};

const handleResponse = {
    checkApiCode(res) {
        if (res.data.code == 7) {
            window.location.href = API_CONFIG.baseURL + '#/unlogin';
        } else if (res.data.message) {
            MessageBox.alert(res.data.message + `(${res.data.code})`, {
                type: 'warning',
                callback: () => {
                    if (
                        res.data.code === 1001 ||
                        res.data.code === 1003 ||
                        res.data.code === 1004
                    ) {
                        removeUserInfo();
                        window.location.href = `${API_CONFIG.baseURL}oauthWithPlatform?url=` + encodeURIComponent(window.location.href);
                    }
                },
            });
        } else {
            MessageBox.alert(`未知错误${JSON.stringify(res)}`, {
                type: 'warning',
            });
        }
    },
};


const api = {
    post(url = '', params = {}, options = {}) {
        return httpRequest('post', url, params, options);
    },
    get(url = '', params = {}, options = {}) {
        return httpRequest('get', url, params, options);
    },
};
export default {
    ...api,
};
