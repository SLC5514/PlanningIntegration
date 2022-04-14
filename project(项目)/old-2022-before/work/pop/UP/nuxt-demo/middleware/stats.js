// 异步执行需返回一个 Promise
export default function({ route }) {
  console.log('执行路由中间件')
  // return new Promise((resolve, reject) => {
  //   setTimeout(() => {
  //     resolve()
  //   }, 1000)
  // })
}
