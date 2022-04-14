import { _ as _export_sfc, a as __vitePreload } from "./index.7c4d36be.js";
import { i as defineAsyncComponent, j as reactive, k as onMounted, o as openBlock, c as createElementBlock, l as createBaseVNode, t as toDisplayString, u as unref, a as createVNode, F as Fragment, p as pushScopeId, m as popScopeId } from "./vendor.fbe34875.js";
var _imports_0 = "/assets/logo.03d6d6da.png";
var foo = { msg: "hi" };
var Home_vue_vue_type_style_index_0_scoped_true_lang = "";
function __variableDynamicImportRuntime1__(path) {
  switch (path) {
    case "../components/ImportType.vue":
      return __vitePreload(() => import("./ImportType.2bc24b16.js"), true ? ["assets/ImportType.2bc24b16.js","assets/vendor.fbe34875.js"] : void 0);
    default:
      return new Promise(function(resolve, reject) {
        (typeof queueMicrotask === "function" ? queueMicrotask : setTimeout)(reject.bind(null, new Error("Unknown variable dynamic import: " + path)));
      });
  }
}
const _withScopeId = (n) => (pushScopeId("data-v-237461d4"), n = n(), popScopeId(), n);
const _hoisted_1 = /* @__PURE__ */ _withScopeId(() => /* @__PURE__ */ createBaseVNode("h1", null, "Home", -1));
const _hoisted_2 = /* @__PURE__ */ _withScopeId(() => /* @__PURE__ */ createBaseVNode("p", null, [
  /* @__PURE__ */ createBaseVNode("img", {
    src: _imports_0,
    alt: "logo"
  })
], -1));
const _hoisted_3 = { class: "virtual" };
const _hoisted_4 = /* @__PURE__ */ _withScopeId(() => /* @__PURE__ */ createBaseVNode("p", { class: "inter" }, "this will be styled with a font-face", -1));
const _hoisted_5 = { class: "import-meta-url" };
const _hoisted_6 = { class: "protocol" };
const _sfc_main = {
  setup(__props) {
    const ImportType = load("ImportType");
    const Foo = defineAsyncComponent(() => __vitePreload(() => import("./Foo.695f320e.js"), true ? ["assets/Foo.695f320e.js","assets/Foo.a8752494.css","assets/vendor.fbe34875.js"] : void 0).then((mod) => mod.Foo));
    function load(file) {
      return defineAsyncComponent(() => __variableDynamicImportRuntime1__(`../components/${file}.vue`));
    }
    let state = reactive({
      count: 0,
      protocol: "test",
      url: ""
    });
    onMounted(() => {
      state.url = document.querySelector(".import-meta-url").textContent;
    });
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock(Fragment, null, [
        _hoisted_1,
        _hoisted_2,
        createBaseVNode("button", {
          onClick: _cache[0] || (_cache[0] = ($event) => unref(state).count++)
        }, "count is: " + toDisplayString(unref(state).count), 1),
        createVNode(unref(Foo)),
        createBaseVNode("p", _hoisted_3, "msg from virtual module: " + toDisplayString(unref(foo).msg), 1),
        _hoisted_4,
        createBaseVNode("p", _hoisted_5, toDisplayString(unref(state).url), 1),
        createBaseVNode("p", _hoisted_6, toDisplayString(unref(state).protocol), 1),
        createVNode(unref(ImportType))
      ], 64);
    };
  }
};
var Home = /* @__PURE__ */ _export_sfc(_sfc_main, [["__scopeId", "data-v-237461d4"]]);
export { Home as default };
