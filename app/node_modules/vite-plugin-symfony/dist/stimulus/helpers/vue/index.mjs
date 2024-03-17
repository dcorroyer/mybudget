// src/stimulus/helpers/vue/index.ts
import { defineAsyncComponent } from "vue";
var vueComponentsOrLoaders = {};
function registerVueControllerComponents(modules, controllersDir = "./vue/controllers") {
  Object.entries(modules).forEach(([key, module]) => {
    if (typeof module !== "function") {
      vueComponentsOrLoaders[key] = module.default;
    } else {
      vueComponentsOrLoaders[key] = module;
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
      const module = vueComponentsOrLoaders[componentPath];
      vueComponentsOrLoaders[componentPath] = defineAsyncComponent(module);
    }
    return vueComponentsOrLoaders[componentPath];
  }
  window.resolveVueComponent = (name) => {
    return loadComponent(name);
  };
}
export {
  registerVueControllerComponents
};
