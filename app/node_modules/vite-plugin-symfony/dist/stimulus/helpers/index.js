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

// src/stimulus/helpers/index.ts
var helpers_exports = {};
__export(helpers_exports, {
  registerControllers: () => registerControllers,
  startStimulusApp: () => startStimulusApp
});
module.exports = __toCommonJS(helpers_exports);

// src/stimulus/helpers/base.ts
var import_stimulus = require("@hotwired/stimulus");
var import_controllers = __toESM(require("virtual:symfony/controllers"));

// src/stimulus/util.ts
var CONTROLLER_FILENAME_REGEX = /^(?:.*?(controllers)\/|\.?\.\/)?(.+)\.[jt]sx?$/;
var CONTROLLER_SUFFIX_REGEX = /^(.*)(?:[/_-](lazy)?controller)$/;
function getStimulusControllerFileInfos(key, onlyControllersDir = false) {
  const [, controllers, relativePath] = key.match(CONTROLLER_FILENAME_REGEX) || [];
  if (!relativePath || onlyControllersDir && controllers !== "controllers") {
    return {
      identifier: void 0,
      lazy: false
    };
  }
  const [, identifier, lazy] = relativePath.match(CONTROLLER_SUFFIX_REGEX) || [];
  return {
    identifier: (identifier ?? relativePath).replace(/_/g, "-").replace(/\//g, "--"),
    lazy: lazy === "lazy"
  };
}

// src/stimulus/helpers/base.ts
function getLazyController(lazyControllerModule, exportName = "default") {
  return class extends import_stimulus.Controller {
    constructor(context) {
      super(context);
      this.__stimulusLazyController = true;
    }
    initialize() {
      if (this.application.controllers.find((controller) => {
        return controller.identifier === this.identifier && controller.__stimulusLazyController;
      })) {
        return;
      }
      lazyControllerModule().then((controller) => {
        this.application.register(this.identifier, controller[exportName]);
      });
    }
  };
}
function startStimulusApp() {
  const app = import_stimulus.Application.start();
  app.debug = process.env.NODE_ENV === "development";
  for (const controllerName in import_controllers.default) {
    if (!import_controllers.default.hasOwnProperty(controllerName)) {
      continue;
    }
    app.register(controllerName, import_controllers.default[controllerName]);
  }
  return app;
}
function registerControllers(app, modules) {
  Object.entries(modules).forEach(([filePath, importedModule]) => {
    const { identifier, lazy } = getStimulusControllerFileInfos(filePath);
    if (!identifier) {
      throw new Error(`Invalid filePath name ${filePath}`);
    }
    if (typeof importedModule === "function") {
      if (lazy) {
        app.register(identifier, getLazyController(importedModule));
      } else {
        importedModule().then((controllerConstructor) => {
          if (identifier && typeof controllerConstructor.default === "function") {
            app.register(identifier, controllerConstructor.default);
          }
        });
      }
    } else {
      app.register(identifier, importedModule.default);
    }
  });
}
// Annotate the CommonJS export names for ESM import in node:
0 && (module.exports = {
  registerControllers,
  startStimulusApp
});
