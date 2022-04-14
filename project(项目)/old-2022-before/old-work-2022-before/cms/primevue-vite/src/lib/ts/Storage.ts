/**
 * LocalStorage 类
 */

export default class Storage {
  static get(name: string) {
    let data = localStorage.getItem(name);
    if (!data) return data;
    return JSON.parse(data);
  }

  static set(name: string, val: any) {
    return localStorage.setItem(name, JSON.stringify(val));
  }

  static add(name: string, addVal: any) {
    let oVal = Storage.get(name);
    let nVal = oVal.concat(addVal);
    Storage.set(name, nVal);
  }
}
