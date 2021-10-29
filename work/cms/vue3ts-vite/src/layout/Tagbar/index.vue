<script lang="ts" setup>
import { computed, watch, onMounted, ref, nextTick, Ref, ComputedRef } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useStore } from 'vuex';
import path from 'path';

const router = useRouter();
const route = useRoute();
const store = useStore();
const visitedViews = computed(() => store.state.tagsView.visitedViews)
const routes: ComputedRef<any[]> = computed(() => store.state?.permission?.routes || [])

const visible = ref(false);
const top = ref(0);
const left = ref(0);
const selectedTag: any = ref({});
const affixTags: Ref<any[]> = ref([]);

const tagRef: Ref<any[]> = ref([]);
// const scrollPaneRef = ref(null);

watch(() => route.path, () => {
  addTags()
  moveToCurrentTag()
})

watch(visible, (value) => {
  if (value) {
    document.body.addEventListener('click', closeMenu)
  } else {
    document.body.removeEventListener('click', closeMenu)
  }
})

onMounted(() => {
  initTags()
  addTags()
})

function isActive(tag: AnyObject) {
  return tag.path === route.path
}
function isAffix(tag: AnyObject) {
  return tag?.meta?.affix
}
function closeSelectedTag(view: AnyObject) {
  store.dispatch('tagsView/delView', view).then(({ visitedViews }) => {
    if (isActive(view)) {
      toLastView(visitedViews, view)
    }
  })
}
function toLastView(visitedViews: Array<AnyObject>, view: AnyObject) {
  const latestView = visitedViews.slice(-1)[0]
  if (latestView) {
    router.push(latestView.fullPath)
  } else {
    // now the default is to redirect to the home page if there is no tags-view,
    // you can adjust it according to your needs.
    if (view.name === 'Dashboard') {
      // to reload home page
      router.replace({ path: '/redirect' + view.fullPath })
    } else {
      router.push('/')
    }
  }
}
function openMenu(tag: AnyObject, e: MouseEvent, el: any) {
  const menuMinWidth = 105
  const offsetLeft = el.getBoundingClientRect().left // container margin left
  const offsetWidth = el.offsetWidth // container width
  const maxLeft = offsetWidth - menuMinWidth // left boundary
  const l = e.clientX - offsetLeft + 15 // 15: margin right

  if (l > maxLeft) {
    left.value = maxLeft
  } else {
    left.value = l
  }

  top.value = e.clientY
  visible.value = true
  selectedTag.value = tag
}

function initTags() {
  affixTags.value = filterAffixTags(routes.value)
  for (const tag of affixTags.value) {
    // Must have tag name
    if (tag.name) {
      store.dispatch('tagsView/addVisitedView', tag)
    }
  }
}
function filterAffixTags(routes: any[], basePath = '/') {
  let tags: any[] = []
  routes.forEach(route => {
    if (route.meta && route.meta.affix) {
      const tagPath = path.resolve(basePath, route.path)
      tags.push({
        fullPath: tagPath,
        path: tagPath,
        name: route.name,
        meta: { ...route.meta }
      })
    }
    if (route.children) {
      const tempTags = filterAffixTags(route.children, route.path)
      if (tempTags.length >= 1) {
        tags = [...tags, ...tempTags]
      }
    }
  })
  return tags
}

function addTags() {
  const { name } = route
  if (name) {
    store.dispatch('tagsView/addView', route)
  }
  return false
}
function moveToCurrentTag() {
  const tags = tagRef.value
  nextTick(() => {
    for (const tag of tags) {
      if (tag?.to?.path === route.path) {
        // this.$refs.scrollPane.moveToTarget(tag)
        // when query is different then update
        if (tag.to.fullPath !== route.fullPath) {
          store.dispatch('tagsView/updateVisitedView', route)
        }
        break
      }
    }
  })
}

function refreshSelectedTag(view: AnyObject) {
  store.dispatch('tagsView/delCachedView', view).then(() => {
    const { fullPath } = view
    nextTick(() => {
      router.replace({
        path: '/redirect' + fullPath
      })
    })
  })
}
function closeOthersTags() {
  router.push(selectedTag.value)
  store.dispatch('tagsView/delOthersViews', selectedTag.value).then(() => {
    moveToCurrentTag()
  })
}
function closeAllTags(view: AnyObject) {
  store.dispatch('tagsView/delAllViews').then(({ visitedViews }) => {
    if (affixTags.value.some((tag: AnyObject) => tag.path === view.path)) {
      return
    }
    toLastView(visitedViews, view)
  })
}
function closeMenu() {
  visible.value = false
}
</script>

<template>
  <div class="tagbar">
    <router-link
      v-for="(tag, index) in visitedViews"
      :ref="el => tagRef[index] = el"
      :key="tag.path"
      :to="{ path: tag.path, query: tag.query, fullPath: tag.fullPath }"
      custom
      v-slot="{ navigate }"
    >
      <span
        :class="['tags-view-item', isActive(tag) && 'active']"
        @click.left="navigate"
        @click.prevent.middle="!isAffix(tag) ? closeSelectedTag(tag) : ''"
        @contextmenu.prevent="openMenu(tag, $event, $el)"
      >
        <span>{{ tag.title }}</span>
        <span v-if="!isAffix(tag)" class="icon-close" @click.prevent.stop="closeSelectedTag(tag)">x</span>
      </span>
    </router-link>
    <ul v-show="visible" :style="{ left: left + 'px', top: top + 'px' }" class="contextmenu">
      <li @click="refreshSelectedTag(selectedTag)">刷新</li>
      <li v-if="!isAffix(selectedTag)" @click="closeSelectedTag(selectedTag)">关闭</li>
      <li @click="closeOthersTags">关闭其它</li>
      <li @click="closeAllTags(selectedTag)">关闭所有</li>
    </ul>
  </div>
</template>

<style lang="scss" scoped>
.tagbar {
  height: $tagbar-height;
  line-height: $tagbar-height;
  background: #fff;
  border-bottom: 1px solid #d8dce5;
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / 12%), 0 0 3px 0 rgb(0 0 0 / 4%);
  .tags-view-item {
    display: inline-block;
    position: relative;
    cursor: pointer;
    height: 26px;
    line-height: 26px;
    border: 1px solid #d8dce5;
    color: #495060;
    background: #fff;
    padding: 0 8px;
    font-size: 12px;
    margin-left: 5px;
    margin-top: 4px;
    box-sizing: border-box;
    &:first-of-type {
      margin-left: 15px;
    }
    &:last-of-type {
      margin-right: 15px;
    }
    &.active {
      background-color: #42b983;
      color: #fff;
      border-color: #42b983;
      &::before {
        content: "";
        background: #fff;
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        position: relative;
        margin-right: 5px;
      }
    }
    .icon-close {
      display: inline-block;
      width: 16px;
      height: 16px;
      line-height: 1;
      margin-left: 5px;
      vertical-align: 2px;
      border-radius: 50%;
      text-align: center;
      transition: all 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
      transform-origin: 100% 50%;
      &:hover {
        background-color: #b4bccc;
        color: #fff;
      }
    }
  }
  .contextmenu {
    margin: 0;
    background: #fff;
    z-index: 9;
    position: absolute;
    list-style-type: none;
    padding: 5px 0;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 400;
    line-height: 1;
    color: #333;
    box-shadow: 2px 2px 3px 0 rgba(0, 0, 0, 0.3);
    li {
      margin: 0;
      padding: 7px 16px;
      cursor: pointer;
      &:hover {
        background: #eee;
      }
    }
  }
}
</style>
