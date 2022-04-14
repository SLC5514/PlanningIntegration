import axios from 'axios';
import { Message } from 'element-ui';
import router from '../router';
// import Cookie from 'js-cookie';

axios.defaults.timeout = 5000;
axios.defaults.baseURL = 'http://cms-wechat.pop-fashion.com';

axios.defaults.transformRequest = [function (data) {
    let ret = '';
    for (let it in data) {
      ret += encodeURIComponent(it) + '=' + encodeURIComponent(data[it]) + '&';
    }
    return ret;
}]

//http request 拦截器
axios.interceptors.request.use(
    config => {
        // const POP_ACCOUNT = Cookie.get('POP_ACCOUNT');
        // const POP_U_HASH = Cookie.get('POP_U_HASH');
        config.data = config.data;
        config.headers = {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
        // if(POP_ACCOUNT && POP_U_HASH){
        //   config.params = {
        //     'POP_ACCOUNT': POP_ACCOUNT,
        //     'POP_U_HASH': POP_U_HASH
        //   }
        // }
        return config;
    },
    error => {
        return Promise.reject(err);
    }
);


//http response 拦截器
axios.interceptors.response.use(
    response => {
        if (response.data.code == -1) {
            router.push({
                path: "/login",
                query: { redirect: router.currentRoute.query.redirect || router.currentRoute.name } //从哪个页面跳转
            })
        }
        return response;
    },
    error => {
        return Promise.reject(error)
    }
)


/**
 * 封装get方法
 * @param url
 * @param data
 * @returns {Promise}
 */

export function get(url, params = {}) {
    return new Promise((resolve, reject) => {
        axios.get(url, {
                params: params
            })
            .then(response => {
                resolve(response.data);
            })
            .catch(err => {
                reject(err)
            })
    })
}


/**
 * 封装post请求
 * @param url
 * @param data
 * @returns {Promise}
 */

export function post(url, data = {}) {
    return new Promise((resolve, reject) => {
        axios.post(url, data)
            .then(response => {
                resolve(response.data);
            }, err => {
                reject(err)
            })
    })
}

/**
 * 封装patch请求
 * @param url
 * @param data
 * @returns {Promise}
 */

export function patch(url, data = {}) {
    return new Promise((resolve, reject) => {
        axios.patch(url, data)
            .then(response => {
                resolve(response.data);
            }, err => {
                reject(err)
            })
    })
}

/**
 * 封装put请求
 * @param url
 * @param data
 * @returns {Promise}
 */

export function put(url, data = {}) {
    return new Promise((resolve, reject) => {
        axios.put(url, data)
            .then(response => {
                resolve(response.data);
            }, err => {
                reject(err)
            })
    })
}
