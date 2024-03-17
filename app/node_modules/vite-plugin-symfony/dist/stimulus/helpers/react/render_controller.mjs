// src/stimulus/helpers/react/render_controller.ts
import React from "react";
import { createRoot } from "react-dom/client";
import { Controller } from "@hotwired/stimulus";
var render_controller_default = class extends Controller {
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
      this._renderReactElement(React.createElement(component, props, null));
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
      element.root = createRoot(this.element);
    }
    element.root.render(reactElement);
  }
  dispatchEvent(name, payload) {
    this.dispatch(name, { detail: payload, prefix: "react" });
  }
};
export {
  render_controller_default as default
};
