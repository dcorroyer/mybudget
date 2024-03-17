var __defProp = Object.defineProperty;
var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
var __getOwnPropNames = Object.getOwnPropertyNames;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __export = (target, all) => {
  for (var name in all)
    __defProp(target, name, { get: all[name], enumerable: true });
};
var __copyProps = (to, from, except, desc) => {
  if (from && typeof from === "object" || typeof from === "function") {
    for (let key of __getOwnPropNames(from))
      if (!__hasOwnProp.call(to, key) && key !== except)
        __defProp(to, key, { get: () => from[key], enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable });
  }
  return to;
};
var __toCommonJS = (mod) => __copyProps(__defProp({}, "__esModule", { value: true }), mod);

// src/stimulus/helpers/vue/index.ts
var vue_exports = {};
__export(vue_exports, {
  registerVueControllerComponents: () => registerVueControllerComponents
});
module.exports = __toCommonJS(vue_exports);
var import_vue = require("vue");
var vueComponentsOrLoaders = {};
function registerVueControllerComponents(modules, controllersDir = "./vue/controllers") {
  Object.entries(modules).forEach(([key, module2]) => {
    if (typeof module2 !== "function") {
      vueComponentsOrLoaders[key] = module2.default;
    } else {
      vueComponentsOrLoaders[key] = module2;
    }
  });
  function loadComponent(name) {
    const componentPath = `${controllersDir}/${name}.vue`;
    if (!(componentPath in vueComponentsOrLoaders)) {
      const possibleValues = Object.keys(vueComponentsOrLoaders).map(
        (key) => key.replace("./", "").replace(".vue", "")
      );
      throw new Error(`Vue controller "${name}" does not exist. Possible values: ${possibleValues.join(", ")}`);
    }
    if (typeof vueComponentsOrLoaders[componentPath] === "function") {
      const module2 = vueComponentsOrLoaders[componentPath];
      vueComponentsOrLoaders[componentPath] = (0, import_vue.defineAsyncComponent)(module2);
    }
    return vueComponentsOrLoaders[componentPath];
  }
  window.resolveVueComponent = (name) => {
    return loadComponent(name);
  };
}
// Annotate the CommonJS export names for ESM import in node:
0 && (module.exports = {
  registerVueControllerComponents
});
