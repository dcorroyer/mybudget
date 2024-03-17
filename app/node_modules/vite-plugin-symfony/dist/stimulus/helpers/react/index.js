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

// src/stimulus/helpers/react/index.ts
var react_exports = {};
__export(react_exports, {
  registerReactControllerComponents: () => registerReactControllerComponents
});
module.exports = __toCommonJS(react_exports);
var reactImportedModules = {};
function registerReactControllerComponents(modules, controllersDir = "./react/controllers") {
  reactImportedModules = { ...reactImportedModules, ...modules };
  window.resolveReactComponent = (name) => {
    const reactModule = reactImportedModules[`${controllersDir}/${name}.jsx`] || reactImportedModules[`${controllersDir}/${name}.tsx`];
    if (typeof reactModule === "undefined") {
      const possibleValues = Object.keys(reactImportedModules).map(
        (key) => key.replace(`${controllersDir}/`, "").replace(".jsx", "").replace(".tsx", "")
      );
      throw new Error(`React controller "${name}" does not exist. Possible values: ${possibleValues.join(", ")}`);
    }
    return reactModule;
  };
}
// Annotate the CommonJS export names for ESM import in node:
0 && (module.exports = {
  registerReactControllerComponents
});
