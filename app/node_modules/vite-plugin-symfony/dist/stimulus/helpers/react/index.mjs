// src/stimulus/helpers/react/index.ts
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
export {
  registerReactControllerComponents
};
