import List from '@/components/List';
import Vue from 'vue';

describe('List.vue', () => {
  it('displays items from the list', () => {
    // our test goes here
    const Constructor = Vue.extend(List)
    const vm = new Constructor().$mount()

    // assert that component text contains items from the list
    expect(vm.$el.textContent).to.contain('play games');
  })

  it('adds a new item to list on click', () => {
    // build component
    const Constructor = Vue.extend(List)
    const vm = new Constructor().$mount()

    // set value of new item
    vm.newItem = 'brush my teeth';

    // find button
    const button = vm.$el.querySelector('button');

    // simulate click event
    const clickEvent = new window.Event('click');
    button.dispatchEvent(clickEvent);
    vm._watcher.run();

    //assert list contains new item
    expect(vm.$el.textContent).to.contain('brush my teeth');
    expect(vm.listItems).to.contain('brush my teeth');
  })
})
