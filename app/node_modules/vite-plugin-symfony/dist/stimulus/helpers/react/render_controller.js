var __create = Object.create;
var __defProp = Object.defineProperty;
var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
var __getOwnPropNames = Object.getOwnPropertyNames;
var __getProtoOf = Object.getPrototypeOf;
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
var __toESM = (mod, isNodeMode, target) => (target = mod != null ? __create(__getProtoOf(mod)) : {}, __copyProps(
  // If the importer is in node compatibility mode or this is not an ESM
  // file that has been converted to a CommonJS file using a Babel-
  // compatible transform (i.e. "__esModule" has not been set), then set
  // "default" to the CommonJS "module.exports" for node compatibility.
  isNodeMode || !mod || !mod.__esModule ? __defProp(target, "default", { value: mod, enumerable: true }) : target,
  mod
));
var __toCommonJS = (mod) => __copyProps(__defProp({}, "__esModule", { value: true }), mod);

// src/stimulus/helpers/react/render_controller.ts
var render_controller_exports = {};
__export(render_controller_exports, {
  default: () => render_controller_default
});
module.exports = __toCommonJS(render_controller_exports);
var import_react = __toESM(require("react"));
var import_client = require("react-dom/client");
var import_stimulus = require("@hotwired/stimulus");
var render_controller_default = class extends import_stimulus.Controller {
  static values = {
    component: String,
    props: Object
  };
  connect() {
    const props = this.propsValue ? this.propsValue : null;
    this.dispatchEvent("connect", { component: this.componentValue, props });
    if (!this.componentValue) {
      throw new Error("No component specified.");
    }
    const importedReactModule = window.resolveReactComponent(this.componentValue);
    const onload = (reactModule) => {
      const component = reactModule.default;
      this._renderReactElement(import_react.default.createElement(component, props, null));
      this.dispatchEvent("mount", {
        componentName: this.componentValue,
        component,
        props
      });
    };
    if (typeof importedReactModule === "function") {
      importedReactModule().then(onload);
    } else {
      onload(importedReactModule);
    }
  }
  disconnect() {
    this.element.root.unmount();
    this.dispatchEvent("unmount", {
      component: this.componentValue,
      props: this.propsValue ? this.propsValue : null
    });
  }
  _renderReactElement(reactElement) {
    const element = this.element;
    if (!element.root) {
      element.root = (0, import_client.createRoot)(this.element);
    }
    element.root.render(reactElement);
  }
  dispatchEvent(name, payload) {
    this.dispatch(name, { detail: payload, prefix: "react" });
  }
};
