<!-- <script lang="ts" setup>
const props = defineProps({
  txt: String,
})
const emit = defineEmits(['edit'])
const text = '这是子级文本'
const myEdit = () => {
  console.log('child edit', props.txt) // child edit 这是父级文本
}
onMounted(() => {
  emit('edit') // parent edit 这是子级文本
})
// 抛出
defineExpose({
  text,
  myEdit,
})
</script> -->
<script lang="ts">
export default defineComponent({
  props: {
    txt: String,
  },
  emits: ['edit'],
  setup(props, { emit }) {
    const { txt } = toRefs(props)
    const text = '这是子级文本'
    const myEdit = () => {
      console.log('child edit', txt.value) // child edit 这是父级文本
    }
    onMounted(() => {
      emit('edit') // parent edit 这是子级文本
    })
    // 抛出
    return {
      text,
      myEdit,
    }
  },
})
</script>

<template>
  <div>child</div>
</template>
