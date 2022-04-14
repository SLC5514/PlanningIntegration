import { getTemplates } from "@/api";

import defStyle from '@/common/elementModel';
const { eleDefCommonStyle, pageCoverStyle, titleCoverStyle, infoCoverStyle, gridCoverStyle } = defStyle;

export const state = () => ({
  isCollapse: false, // 侧栏导航展开状态
  scale: 100, // 适配倍数
  isResize: false, // 适配重置
  gridScale: 1120 / 2000, // 格子缩放比
  maxElZIndex: 1, // 所有元素当前最大层级

  // 报告数据 type: page text grid-box grid image
  reportData: [
    {
      uuid: 1,
      type: 'page',
      title: '标题',
      style: {
        ...pageCoverStyle
      },
      elements: [
        {
          uuid: 2,
          type: 'text',
          text: '标题',
          style: {
            ...eleDefCommonStyle,
            ...titleCoverStyle
          }
        },
        {
          uuid: 3,
          type: 'text',
          text: '简单的线条和色块构成特点十足的牛牛形象图案受到小朋友们的喜爱。该类型柔和色调的图案适合运用于针织类面料的满版印花，适合呈现在如家居服，连衣裙，婴幼童爬服哈衣等款式上。',
          style: {
            ...eleDefCommonStyle,
            ...infoCoverStyle
          }
        },
        {
          id: 666,
          type: 'grid-box',
          grids: [
            {
              uuid: 5,
              type: 'grid',
              style: {
                ...gridCoverStyle,
                ...{ x: 0, y: 0, width: 2000, height: 1125 },
                left: 0 + 40 / (1120 / 2000),
                top: 0 + 150 / (1120 / 2000)
              },
              clip: {
                w: 0,
                h: 0,
                x: 0,
                y: 0,
                scale: 1,
              }
            }
          ]
        },
        {
          uuid: 4,
          type: 'image',
          style: {
            ...eleDefCommonStyle,
            position: 'absolute',
            left: 40,
            top: 140,
            width: 300,
            height: 200,
            lineHeight: 1,
          },
          clip: {
            w: 0,
            h: 0,
            x: 0,
            y: 0,
            scale: 1,
            image: 'https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg'
          }
        },
      ]
    }
  ],
  reportIdx: 0, // 报告下标

  templates: [], // 模板列表
  addSubReportVisible: false, // 新增子报告对话框状态

	activePageUUID: '', // 当前正在编辑的页面uuid
  activeElementUUID: '', // 画板中选中的元素uuid

  isHotShift: false, // 点击热键shift
  isClipImg: false, // 图片裁剪中
});

export const mutations = {
  // 侧栏导航展开状态
  setIsCollapse(state, val) {
    state.isCollapse = val;
  },
  // 缩放控制
  scaleChange(state, val) {
    state.scale = val;
  },
  // 适配重置
  isResizeChange(state, val) {
    state.isResize = val;
  },
  // 所有元素当前最大层级
  setMaxElZIndex(state, val) {
    state.maxElZIndex = val;
  },
  // 获取模板数据
  GET_TEMPLATES(state, val) {
    state.templates = val;
  },
  // 新增子报告对话框状态
  setAddSubReport(state, val) {
    state.addSubReportVisible = val;
  },
  // 设置报告数据
  setReportItemData(state, { keys = [], key, val }) {
    let data = state.reportData;
    try {
      for (let k in keys) {
        data = data[keys[k]];
      }
      if (keys.length) {
        if (key !== undefined) {
          if (val !== undefined) {
            data[key] = val; // 编辑
          } else if (key !== undefined && !isNaN(key) && key > -1) {
            data.splice(key, 1); // 删除
          }
        } else if (Array.isArray(data)) {
          data.push(val); // 新增 元素
        } else if (val !== undefined) {
          data = val; // 编辑
        }
      } else if (key !== undefined && !isNaN(key) && key > -1) {
        data.splice(key, 1); // 删除
      } else if (val !== undefined) {
        data.push(val); // 新增 页面
      }
    } catch {}
  },
  // 设置报告下标
  setReportIdx(state, val) {
    state.reportIdx = val;
  },
  // 设置画板中选中的元素uuid
  setActiveElementUUID(state, val) {
    state.activeElementUUID = val;
  },
  // 设置点击热键shift
  setIsHotShift(state, val) {
    state.isHotShift = val;
  },
  // 设置图片裁剪中
  setIsClipImg(state, val) {
    state.isClipImg = val;
  },
};

export const actions = {
  // 获取模板数据
  async getTemplates({ commit }) {
    return new Promise((resolve, reject) => {
      getTemplates().then(res => {
        if (res.code === 1) {
          commit("GET_TEMPLATES", res.data);
          resolve(res);
        } else {
          reject(res);
        }
      }).catch(err => {
        reject(err);
      })
    })
  },
};

export const getters = {
  isCollapse: state => state.isCollapse,
  scale: state => state.scale,
  isResize: state => state.isResize,
  gridScale: state => state.gridScale,
  maxElZIndex: state => state.maxElZIndex,
  reportIdx: state => state.reportIdx,
  reportData: state => state.reportData,
  templates: state => state.templates,
  addSubReportVisible: state => state.addSubReportVisible,
  activeElementUUID: state => state.activeElementUUID,
  isHotShift: state => state.isHotShift,
  isClipImg: state => state.isClipImg,
};
