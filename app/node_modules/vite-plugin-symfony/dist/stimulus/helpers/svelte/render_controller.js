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

// src/stimulus/helpers/svelte/render_controller.ts
var render_controller_exports = {};
__export(render_controller_exports, {
  default: () => render_controller_default
});
module.exports = __toCommonJS(render_controller_exports);
var import_stimulus = require("@hotwired/stimulus");
var render_controller_default = class extends import_stimulus.Controller {
  app;
  props;
  intro;
  static values = {
    component: String,
    props: Object,
    intro: Boolean
  };
  connect() {
    this.element.innerHTML = "";
    this.props = this.propsValue ?? void 0;
    this.intro = this.introValue ?? void 0;
    this.dispatchEvent("connect");
    const importedSvelteModule = window.resolveSvelteComponent(this.componentValue);
    const onload = (svelteModule) => {
      const Component = svelteModule.default;
      this._destroyIfExists();
      this.app = new Component({
        target: this.element,
        props: this.props,
        intro: this.intro
      });
      this.element.root = this.app;
      this.dispatchEvent("mount", {
        component: Component
      });
    };
    if (typeof importedSvelteModule === "function") {
      importedSvelteModule().then(onload);
    } else {
      onload(importedSvelteModule);
    }
  }
  disconnect() {
    this._destroyIfExists();
    this.dispatchEvent("unmount");
  }
  _destroyIfExists() {
    if (this.element.root !== void 0) {
      this.element.root.$destroy();
      delete this.element.root;
    }
  }
  dispatchEvent(name, payload = {}) {
    const detail = {
      componentName: this.componentValue,
      props: this.props,
      intro: this.intro,
      ...payload
    };
    this.dispatch(name, { detail, prefix: "svelte" });
  }
};
