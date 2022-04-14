// 元素默认样式/属性
export let eleDefCommonStyle = {
  position: 'relative',
  width: 'auto',
  height: 'auto',
  left: 0,
  top: 0,
  borderColor: 'rgba(0, 0, 0, 1)',
  borderRadius: 0,
  borderWidth: 0,
  paddingTop: 0,
  paddingBottom: 0,
  paddingLeft: 0,
  paddingRight: 0,
  boxShadow: 'none',
  opacity: 1,
  // 属性
  rotate: 0,

  // 文字默认样式
  color: 'rgba(0, 0, 0, 1)',
  fontSize: 14,
	fontWeight: 500,
	textAlign: 'left',
  lineHeight: 1.5,
  letterSpacing: 0,

  // 图片默认样式
  backgroundImage: '',
  backgroundColor: 'rgba(0, 0, 0, 0)',
  backgroundSize: 'cover'
}

export let eleDefCommonAttr = {
  shadowColor: 'rgba(0, 0, 0, 1)',
  shadowH: 0,
  shadowV: 0,
  shadowBlur: 0,
  collapse: [],
  link: '',
}

// 单页覆盖样式
export let pageCoverStyle = {
  width: 1200,
  height: 820,
  backgroundColor: 'rgba(255, 255, 255, 1)',
}

// 标题覆盖样式
export let titleCoverStyle = {
  display: 'inline-block',
  position: 'absolute',
  left: 40,
  top: 40,
  height: 50,
  lineHeight: 50,
  paddingLeft: 10,
  paddingRight: 10,
  color: 'rgba(255, 255, 255, 1)',
  backgroundColor: 'rgba(51, 51, 51, 1)'
}

// 描述覆盖样式
export let infoCoverStyle = {
  position: 'absolute',
  left: 40,
  top: 100,
  width: 1120,
  height: 42
}

// 格子覆盖样式
export let gridCoverStyle = {
  position: 'absolute',
  rotate: 0,
  backgroundColor: 'rgba(246, 246, 246, 1)'
}

// 图片覆盖样式
export let imgCoverStyle = {
}

// 背景覆盖样式
export let bgImgCoverStyle = {
}

export default {
  eleDefCommonStyle,
  eleDefCommonAttr,

  pageCoverStyle,
  titleCoverStyle,
  infoCoverStyle,
  gridCoverStyle,

  imgCoverStyle,
  bgImgCoverStyle
}
