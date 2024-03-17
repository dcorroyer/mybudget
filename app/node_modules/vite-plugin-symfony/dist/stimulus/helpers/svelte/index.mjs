// src/stimulus/helpers/svelte/index.ts
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
export {
  registerSvelteControllerComponents
};
