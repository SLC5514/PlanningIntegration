import { defineComponent, h } from 'vue'

export default defineComponent({
  name: 'button',
  render() {
    return (
      <div>test</div>
    )
    // return h(
    //   'button',
    //   {
    //     style: {}
    //   },
    //   this.text || '按钮'
    // )
  },
  props: {
    text: {
      type: String,
      default: '按钮'
    }
  }
})
