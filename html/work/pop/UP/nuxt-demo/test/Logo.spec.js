import { mount } from '@vue/test-utils'
import Logo from '@/components/Logo.vue'

describe('Logo', () => {
  test('is a Vue instance', () => {
    const wrapper = mount(Logo)
    expect(wrapper.isVueInstance()).toBeTruthy()
  })

  /**
   * jest断言归纳
   */

  /* 普通匹配器 */

  // toBe - 来测试是否完全相等
  // toEqual - 检查某个对象的值
  // not - 测试相反的用例

  test('two plus two is four', () => {
    expect(2 + 2).toBe(4)
  })

  test('object assignment', () => {
    const data = { one: 1 }
    data.two = 2
    expect(data).toEqual({ one: 1, two: 2 })
  })

  test('null', () => {
    const n = null
    expect(n).not.toBeUndefined()
    expect(n).not.toBeTruthy()
  })

  /* 布尔值匹配器 */

  // toBeNull - 只匹配 null
  // toBeUndefined - 只匹配 undefined
  // toBeDefined - 与 toBeUndefined 相反
  // toBeTruthy - 匹配任何 if 语句为真
  // toBeFalsy - 匹配任何 if 语句为假

  test('null', () => {
    const n = null
    expect(n).toBeNull()
    expect(n).toBeDefined()
    expect(n).not.toBeUndefined()
    expect(n).not.toBeTruthy()
    expect(n).toBeFalsy()
  })

  test('zero', () => {
    const z = 0
    expect(z).not.toBeNull()
    expect(z).toBeDefined()
    expect(z).not.toBeUndefined()
    expect(z).not.toBeTruthy()
    expect(z).toBeFalsy()
  })

  /* 数字匹配器 */

  // toBeGreaterThan - 大于
  // toBeGreaterThanOrEqual 大于等于
  // toBeLessThan - 小于
  // toBeLessThanOrEqual - 小于等于
  // toBeCloseTo - 浮点数比较

  test('two plus two', () => {
    const value = 2 + 2
    expect(value).toBeGreaterThan(3)
    expect(value).toBeGreaterThanOrEqual(3.5)
    expect(value).toBeLessThan(5)
    expect(value).toBeLessThanOrEqual(4.5)

    // toBe 和 toEqual 对于数字来说是一样的
    expect(value).toBe(4)
    expect(value).toEqual(4)
  })

  // 对于比较浮点数的相等，应该使用 toBeCloseTo
  test('两个浮点数字相加', () => {
    const value = 0.1 + 0.2 // 0.30000000000000004
    // expect(value).toBe(0.3) // 这句会报错，因为 js 浮点数有舍入误差
    expect(value).toBeCloseTo(0.3) // 这句可以运行
  })

  /* 字符串匹配器 */

  // toMatch - 正则表达式的字符

  test('there is no I in team', () => {
    expect('team').not.toMatch(/I/)
  })

  test('but there is a "stop" in Christoph', () => {
    expect('Christoph').toMatch(/stop/)
  })

  // toHaveLength(number) - 判断一个有长度的对象的长度

  test('should toHaveLength', () => {
    expect([1, 2, 3]).toHaveLength(3)
    expect('abc').toHaveLength(3)
    expect('').not.toHaveLength(5)
  })

  /* 数组匹配器 */

  // toContain(item) - 判断数组是否包含特定子项
  // toContainEqual(item) - 判断数组中是否包含一个特定对象

  test('购物清单（shopping list）里面有啤酒（beer）', () => {
    const shoppingList = [
      'diapers',
      'kleenex',
      'trash bags',
      'paper towels',
      'beer'
    ]
    expect(shoppingList).toContain('beer')
  })

  test('is delicious and not sour', () => {
    function myBeverages() {
      return [
        { delicious: true, sour: false },
        { delicious: false, sour: true }
      ]
    }
    const myBeverage = { delicious: true, sour: false }
    expect(myBeverages()).toContainEqual(myBeverage)
  })

  /* 对象匹配器 */

  // toMatchObject(object) - 判断一个对象嵌套的 key 下面的 value 类型
  // toHaveProperty(keyPath, value) - 判断在指定的 path 下是否有这个属性

  test('the house has my desired features', () => {
    const houseForSale = {
      bath: true,
      bedrooms: 4,
      kitchen: {
        amenities: ['oven', 'stove', 'washer'],
        area: 20,
        wallColor: 'white'
      }
    }
    const desiredHouse = {
      bath: true,
      kitchen: {
        amenities: ['oven', 'stove', 'washer'],
        wallColor: expect.stringMatching(/white|yellow/)
      }
    }
    expect(houseForSale).toMatchObject(desiredHouse)
  })

  // Object containing house features to be tested
  test('this house has my desired features', () => {
    const houseForSale = {
      bath: true,
      bedrooms: 4,
      kitchen: {
        amenities: ['oven', 'stove', 'washer'],
        area: 20,
        wallColor: 'white'
      }
    }

    // Simple Referencing
    expect(houseForSale).toHaveProperty('bath')
    expect(houseForSale).toHaveProperty('bedrooms', 4)

    expect(houseForSale).not.toHaveProperty('pool')

    // Deep referencing using dot notation
    expect(houseForSale).toHaveProperty('kitchen.area', 20)
    expect(houseForSale).toHaveProperty('kitchen.amenities', [
      'oven',
      'stove',
      'washer'
    ])

    expect(houseForSale).not.toHaveProperty('kitchen.open')

    // Deep referencing using an array containing the keyPath
    expect(houseForSale).toHaveProperty(['kitchen', 'area'], 20)
    expect(houseForSale).toHaveProperty(
      ['kitchen', 'amenities'],
      ['oven', 'stove', 'washer']
    )
    expect(houseForSale).toHaveProperty(['kitchen', 'amenities', 0], 'oven')

    expect(houseForSale).not.toHaveProperty(['kitchen', 'open'])
  })

  /* 其他 */

  // toThrow - 要测试的特定函数会在调用时抛出一个错误
  // resolves 和 rejects - 用来测试 promise
  // toHaveBeenCalled - 用来判断一个函数是否被调用过
  // toHaveBeenCalledTimes(number) - 判断函数被调用过几次

  test('compiling android goes as expected', () => {
    function compileAndroidCode() {
      throw new Error('you are using the wrong JDK')
    }

    expect(compileAndroidCode).toThrow()
    expect(compileAndroidCode).toThrow(Error)

    // You can also use the exact error message or a regexp
    expect(compileAndroidCode).toThrow('you are using the wrong JDK')
    expect(compileAndroidCode).toThrow(/JDK/)
  })

  // resolves
  test('resolves to lemon', () => {
    // make sure to add a return statement
    return expect(Promise.resolve('lemon')).resolves.toBe('lemon')
  })

  // rejects
  // test('resolves to lemons', async () => {
  //   await expect(Promise.resolve('lemon')).resolves.toBe('lemon')
  //   await expect(Promise.resolve('lemon')).resolves.not.toBe('octopus')
  // })

  // describe('drinkAll', () => {
  //   test('drinks something lemon-flavored', () => {
  //     const drink = jest.fn()
  //     drinkAll(drink, 'lemon')
  //     expect(drink).toHaveBeenCalled()
  //   })
  //   test('does not drink something octopus-flavored', () => {
  //     const drink = jest.fn()
  //     drinkAll(drink, 'octopus')
  //     expect(drink).not.toHaveBeenCalled()
  //   })
  // })

  // test('drinkEach drinks each drink', () => {
  //   const drink = jest.fn()
  //   drinkEach(drink, ['lemon', 'octopus'])
  //   expect(drink).toHaveBeenCalledTimes(2)
  // })

  /* 未整理部分 */

  // lastCalledWith
  // toBeCalledWith
  // toHaveBeenCalledWith
  // toHaveBeenLastCalledWith
  // toBeInstanceOf
  // toMatchSnapshot
  // toThrowError
  // toThrowErrorMatchingSnapshot
})
