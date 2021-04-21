/**
 * 常用: boolean, number, string
 * 数组: number[] 或 Array<number>, ReadonlyArray<number>
 * 元组: [string, number]
 * 枚举: enum Color {Red = 1, Green, Blue}
 * 其它: any, void,
 *      null, undefinde,
 *      object, symbol, 
 *      never
 * 类型断言: (变量 as string) 或 (<string>变量) 不包含对应类型的方法
 * 别名: type Lady = { name: string, age: Number }、type girl = stirng;（可以是基本类型）
 * 接口: const girl = { name: "大脚", age: 18, bust: 94 };（必须是对象类型）
 * 泛型: <类型变量>: 泛型函数(eg: let fanFn = <T>(a: T): T => a)、泛型类
 * 命名空间: namespace
 */

/**
 * 基础静态类型
 */

let num: number = 1;
let str: string = '1';
// num = '1'; Error(不论var还是let)
// str.（拥有此对应类型的方法）
// str.toFixed(2); Error

let numArr: number[] = [1, 2]; // 每一项类型都需相同类型
let anyArr: (string | number)[] = ['1', 2];

// 元组类型允许表示一个已知元素数量和类型的数组，各元素的类型不必相同
let anArr: [string, number] = ['1', 2]; // 每一项必须完全符合对应类型
const xiaojiejies: [string, string, number][] = [
    ["dajiao", "teacher", 28],
    ["liuying", "teacher", 18],
    ["cuihua", "teacher", 25],
];

enum Color {Red = 3, Green, Blue};
let colorA = Color[4]; // Green
let colorB = Color.Green; // 4
console.log(colorA, colorB);

let anyA: any = null;
anyA = undefined;
anyA = '1';

function fn1(): void {}; // 表示没有任何类型
// let fn2: void = null; // void 只能复制 null 和 undefined; Error ts(2322)
let fn3: void = undefined;

// let nullA: null = 1; Error
let nuA: null = null;
let unB: undefined = undefined;
// num = nuA; success 未指定 --strictNullChecks 标记
// num = nuA; Error 指定 --strictNullChecks 标记

// let objA: object = null; // 除 number，string，boolean，symbol，null 或 undefined 之外的类型; Error ts(2322)
let objB: object = {};
let objC: object = [];

function error(): never { // 永不存在的值的类型
    throw new Error('msg');
};

// let sym1 = Symbol(); // 通过Symbol构造函数创建; tsconfig.json "target": "es6"或更高
// let sym2 = Symbol('key');
// let sym3 = Symbol('key');
// console.log(sym2 === sym3); // 唯一的

type Lady = { name: string, age: Number };
const xiaoJieJies: Lady[] = [
    { name: "刘英", age: 18 },
    { name: "谢大脚", age: 28 },
];
// 也可以使用类
class Madam { // "strictPropertyInitialization": true
    name: string; // Error 属性“name”没有初始化表达式，且未在构造函数中明确赋值。ts(2564)
    age: number; // 同上
    constructor() {
        this.name = ''; // 需赋值
        this.age = 0;
    }
}
const xiaoJieJies2: Madam[] = [
    { name: "刘英", age: 18 },
    { name: "谢大脚", age: 28 },
];
console.log(xiaoJieJies2)

/**
 * 接口/自定义类型
 */

interface Girl {
    name: string;
    uname?: string; // 可选
    readonly age: number; // 只读
    [propName: string]: any; // 索引签名
    say(): string; // 函数类型
};
const xiaohong: Girl = {
    name: "小红",
    age: 18, // 不可缺少 Girl 中的字段并保持类型一致
    sex: '女',
    say() {
        return '欢迎光临!'
    }
};
// 接口和类的约束
class XiaoJieJie implements Girl {
    name = "刘英";
    age = 18;
    bust = 90;
    say() {
        return "欢迎光临!";
    }
};
// 接口间的继承
interface Teacher extends Girl {
    teach(): string;
};

/* 函数类型 */
interface SearchFunc {
    (source: string, subString: string): boolean; // 定义函数类型(普通参数)
};
let mySearch1: SearchFunc;
mySearch1 = function(source: string, subString: string) {
    let result = source.search(subString);
    return result > -1;
};
let mySearch2: SearchFunc;
mySearch2 = function(src: string, sub: string): boolean { // 函数的参数名不需要与接口里定义的名字相匹配
    let result = src.search(sub);
    return result > -1;
};
let mySearch3: SearchFunc;
mySearch3 = function(src, sub) { // 会推断出参数类型
    let result = src.search(sub);
    return result > -1;
};

interface SearchFunc2 {
    ({source, subString}: {source: string, subString: string}): boolean; // 定义函数类型(对象参数)
};
let mySearch4: SearchFunc2;
mySearch4 = function({source, subString}): boolean { // 函数的 对象 参数名必须与接口里定义的名字相匹配
    let result = source.search(subString);
    return result > -1;
};

/* 可索引的类型 */
interface StringArray {
    [index: number]: string;
};
let myArray: StringArray;
myArray = ["Bob", "Fred"];
let myStr: string = myArray[0]; // 这个索引签名表示了当用 number 去索引 StringArray 时会得到 string 类型的返回值

/* ts 中类的概念和使用 */
// 类的继承、类的重写 extends super（指父类的属性和方法）
class Lady2 {
    content = "Hi，帅哥";
    sayHello() {
        return this.content;
    }
}
class XiaoJieJie2 extends Lady2 {
    sayLove() {
        return "I love you!";
    }
    // sayHello() {
    //     return "Hi , honey!";
    // }
    sayHello() {
        return super.sayHello() + "。你好！";
    }
}

// 类的访问类型 public、private、protected, readonly
class Person {
    public name: string; // 公共的 允许在类的 内部和外部 被调用（默认声明）
    private _age: number = 0; // 私有的 只允许在类的 内部 被调用
    protected sex: string = ''; // 受保护的 允许在类 内部 和 继承的子类内部 被调用
    public readonly phone: number = 0; // 只读
    get age() {
        return this._age
    }
}
const person = new Person()
person.name = '小明'
// person.age = 18 Error
// person.sex = '女' Error
console.log(person.name)
console.log(person.age)

// 类的构造函数 constructor 子类必须调用 super()
class Person2 {
    public name: string;
    constructor(name: string) {
        this.name=name
    }
}
const person2 = new Person2('jspang')
console.log(person2.name)
// 简写
class Person3 {
    constructor(public name: string){}
}
const person3= new Person3('jspang')
console.log(person3.name)
// 继承的构造函数
interface Person4 {
    age: number;
}
class Person4 {
    constructor(public name: string) {}
}
class Teacher extends Person4 { // 类与接口重名 不报错? 最好不要重名
    constructor(public age: number) {
        super('jspang')
    }
}
console.log(new Teacher(18))

/* TS 类的 Getter、Setter 和 static 使用 */
class Xiaojiejie {
    constructor(private _age: number) {}
    get age() {
        return this._age - 10
    }
    set age(age: number) {
        this._age = age + 3
    }
}
const dajiao = new Xiaojiejie(28)
dajiao.age = 25
console.log(dajiao.age)
// static
// class Girl {
//     sayLove() {
//         return "I Love you";
//     }
// }
// const girl = new Girl();
// console.log(girl.sayLove());
class Girl {
    // 静态声明 不用实例化
    static sayLove() {
        return "I Love you";
    }
}
console.log(Girl.sayLove());

// 抽象类的使用 abstract
abstract class Girl2 {
    abstract skill(): any  // 因为是抽象方法，所以这里不能写括号
}
class Waiter extends Girl2 {
    skill() {
        console.log('大爷，请喝水！')
    }
}
class BaseTeacher extends Girl2 {
    skill() {
        console.log('大爷，来个泰式按摩吧！')
    }
}
class seniorTeacher extends Girl2 {
    skill() {
        console.log('大爷，来个SPA全身按摩吧！')
    }
}

// 联合类型和类型保护

interface Waiter {
    anjiao: boolean;
    say(): any;
}
interface Teacher {
    anjiao: boolean;
    skill(): any;
}
function judgeWho(animal: Waiter | Teacher) { // 联合类型
    // 类型保护-类型断言
    // if (animal.anjiao) {
    //     (animal as Teacher).skill();
    // }else{
    //     (animal as Waiter).say();
    // }
    // 类型保护-in 语法
    if ("skill" in animal) {
        animal.skill();
    } else {
        animal.say();
    }
}
judgeWho({
    anjiao: false,
    say() {
        console.log('123');
    },
    skill() {
        console.log('456');
    }
})

// 类型保护-typeof 语法
// function add(first: string | number, second: string | number) {
//     if (typeof first === "string" || typeof second === "string") {
//         return `${first}${second}`;
//     }
//     return first + second;
// }

// 类型保护-instanceof 语法
// class NumberObj {
//     count: number = 0;
// }
// function addObj(first: object | NumberObj, second: object | NumberObj) {
//     if (first instanceof NumberObj && second instanceof NumberObj) {
//         return first.count + second.count;
//     }
//     return 0;
// }

// 泛型函数
function join <T> (first: T, second: T) {
    return `${first}${second}`;
}
join<string>("jspang", ".com");
join<number>(1, 2);
join(3, 4); // 类型参数推断
// join(3, '4'); Error 类型不一致

function loggingIdentity <T> (arg: T): T {
    // console.log(arg.length); // error: T doesn't have .length
    if (typeof arg === 'string') {
        console.log(arg.length);
    } else if (typeof arg === 'number') {
        console.log(arg);
    }
    return arg;
}
loggingIdentity(5);

// 通用约束（泛型约束 extends 接口/类型等）
interface Lengthwise {
    length: number;
}
// 泛型继承
function loggingIdentity2 <T extends Lengthwise> (arg: T): T {
    console.log(arg.length);  // 现在我们知道它具有.length属性，因此不再有错误
    return arg;
}
// loggingIdentity2(5); // Error 不具有length属性
loggingIdentity2('5');

// 泛型类
class SelectGirl<T> {
    constructor(private girls: T[]) {}
    getGirl(index: number): T {
        return this.girls[index];
    }
}
const selectGirl = new SelectGirl <string> (["大脚", "刘英", "晓红"]);
console.log(selectGirl.getGirl(1));
// 泛型继承
interface Girl3 { name: string; } // 接口重名 可能有继承效果 最好不重名
class SelectGirl2 <T extends Girl3> {
    constructor(private girls: T[]) {}
    getGirl(index: number): string {
        return this.girls[index].name;
    }
}
const selectGirl2 = new SelectGirl2([
    { name: "大脚" },
    { name: "刘英" },
    { name: "晓红" },
]);
console.log(selectGirl2.getGirl(1));

/* 命名空间 */

namespace Test {
    export class Header {
        constructor() {
            const elem = document.createElement('div');
            elem.innerText = 'This is Header';
            document.body.appendChild(elem);
        }
    }
}
console.log(Test)

import { Header, Content, Footer } from "./components/components";
export default class Page {
    constructor() {
        new Header();
        new Content();
        new Footer();
    }
}

/* tsconfig.json 编译配置文件 */

// 生成: tsc --init
