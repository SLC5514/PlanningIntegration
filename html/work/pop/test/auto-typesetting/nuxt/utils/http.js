/**
 * 全局axios封装
 */
import axios from "axios";
import { Message } from "iview";

// create an axios instance
const http = axios.create({
  baseURL: process.env.BASE_API,
  timeout: 5000,
  headers: {
    "Content-Type": "application/x-www-form-urlencoded"
  }
});

// request interceptor
http.interceptors.request.use(
  config => {
    // Do something before request is sent
    return config;
  },
  error => {
    // Do something with request error
    Promise.reject(error);
  }
);

// response interceptor
http.interceptors.response.use(
  response => {
    const res = response.data;
    // if (res.code !== ERROR_CODE_OK) {
    //   Message.error({
    //     content: res.msg || '请求失败, 请稍候重试或联系技术人员！'
    //   });
    // }
    return Promise.resolve(res);
  },
  error => {
    Message.error({
      content: "访问服务器失败！请稍候重试或联系技术人员! " + error.message,
      duration: 3
    });
    return Promise.reject(error);
  }
);

export default http;
