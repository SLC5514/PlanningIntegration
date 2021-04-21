/* Link 文字链接 禁用 阻止默认事件 */
document.querySelectorAll("[s-link~='disabled']").forEach(function (element: Element) {
    element.addEventListener('click', function (e: Event) {
        return preventDefault(e);
    });
});

/* Radio 单选框 选中 */
document.querySelectorAll('[s-radio]').forEach(function (element: Element) {
    element.addEventListener('click', function (this: any, e: Event) {
        let attr = this.getAttribute('s-radio') || '';
        if (attr.indexOf('checked') !== -1) return;
        attr = trim(attr).split(/\s+/);
        attr.push('checked');
        attr = trim(attr.join(' '));
        this.setAttribute('s-radio', attr);
        return preventDefault(e);
    });
});

/* 去除收尾空格 */
function trim(str: string) {
    return str.replace(/^\s+|\s+$/g, '');
}

/* 阻止默认事件 */
function preventDefault(e: Event): boolean {
    if (e && e.preventDefault) {
        e.preventDefault();
    } else if (window.event) {
        window.event.returnValue = false;
    }
    return false;
}
