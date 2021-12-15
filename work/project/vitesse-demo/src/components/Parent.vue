<!-- <script lang="ts" setup>
const childRef = ref() // $refs.childRef
const text = '这是父级文本'
const myEdit = () => {
  console.log('parent edit', childRef.value?.text) // parent edit 这是子级文本
  childRef.value?.myEdit() // child edit 这是父级文本
}
</script> -->
<script lang="ts">
export default defineComponent({
  setup() {
    const childRef = ref() // $refs.childRef
    const text = '这是父级文本'
    const myEdit = () => {
      console.log('parent edit', childRef.value?.text) // parent edit 这是子级文本
      childRef.value?.myEdit() // child edit 这是父级文本
    }
    return {
      text,
      myEdit,
      childRef,
    }
  },
})
</script>

<template>
  parent
  <Child ref="childRef" :txt="text" @edit="myEdit" />
</template>
