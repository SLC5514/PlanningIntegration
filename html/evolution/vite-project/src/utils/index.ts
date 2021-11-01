// 处理样式
export function getPxStyle(paramStyle: {}) {
  let style = {};
  const needUnitStr = ['width', 'height', 'top', 'left', 'paddingTop', 'paddingLeft', 'paddingRight', 'paddingBottom', 'marginTop', 'marginLeft', 'marginRight', 'marginBottom', 'borderWidth', 'fontSize', 'borderRadius', 'letterSpacing'];
  // const textStyleStr = ['paddingTop', 'paddingLeft', 'paddingRight', 'paddingBottom'];
  const transformStr = ["rotate", "fliph", "flipv"];
  const shadowStr = ["shadowColor", "shadowBlur", "shadowH", "shadowV"];
  const noNeedUnitStr = ['x', 'y'];
  for (let key in paramStyle) {
    if (paramStyle[key] === 'auto' || paramStyle[key] === 'normal') { // 属性兼容
      style[key] = paramStyle[key];
    } else if (needUnitStr.includes(key)) {
      // if (textStyleStr.includes(key)) {
      //   if (paramType === 'text') style[key] = paramStyle[key] + "px";
      // } else {
      style[key] = paramStyle[key] + "px";
      // }
    } else if (noNeedUnitStr.includes(key)) {
      continue;
    } else if (key === 'opacity') {
      style[key] = paramStyle[key] / 100;
    } else if (key === 'backgroundImage') {
      style[key] = `url(${paramStyle[key]})`;
    } else if (transformStr.includes(key)) {
      style['transform'] = `rotate(${paramStyle['rotate']}deg)`; // scale(${paramStyle['fliph']}, ${paramStyle['flipv']})
    } else if (shadowStr.includes(key)) {
      // if (paramType === 'text') {
      //   style['textShadow'] = `${paramStyle['shadowH']}px ${paramStyle['shadowV']}px ${paramStyle['shadowBlur']}px ${paramStyle['shadowColor']}`;
      // } else {
        style['boxShadow'] = `${paramStyle['shadowH']}px ${paramStyle['shadowV']}px ${paramStyle['shadowBlur']}px ${paramStyle['shadowColor']}`;
      // }
    } else {
      style[key] = paramStyle[key];
    }
  }
  return style;
}
