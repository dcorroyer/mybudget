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

// src/stimulus/helpers/svelte/index.ts
var svelte_exports = {};
__export(svelte_exports, {
  registerSvelteControllerComponents: () => registerSvelteControllerComponents
});
module.exports = __toCommonJS(svelte_exports);
var svelteImportedModules = {};
function registerSvelteControllerComponents(modules, controllersDir = "./svelte/controllers") {
  svelteImportedModules = { ...svelteImportedModules, ...modules };
  window.resolveSvelteComponent = (name) => {
    const svelteModule = svelteImportedModules[`${controllersDir}/${name}.svelte`];
    if (typeof svelteModule === "undefined") {
      const possibleValues = Object.keys(svelteImportedModules).map(
        (key) => key.replace(`${controllersDir}/`, "").replace(".svelte", "")
      );
      throw new Error(`Svelte controller "${name}" does not exist. Possible values: ${possibleValues.join(", ")}`);
    }
    return svelteModule;
  };
}
// Annotate the CommonJS export names for ESM import in node:
0 && (module.exports = {
  registerSvelteControllerComponents
});
