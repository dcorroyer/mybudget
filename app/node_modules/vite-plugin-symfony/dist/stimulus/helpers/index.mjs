// src/stimulus/helpers/base.ts
import { Application, Controller } from "@hotwired/stimulus";
import thirdPartyControllers from "virtual:symfony/controllers";

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
  return class extends Controller {
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
  const app = Application.start();
  app.debug = process.env.NODE_ENV === "development";
  for (const controllerName in thirdPartyControllers) {
    if (!thirdPartyControllers.hasOwnProperty(controllerName)) {
      continue;
    }
    app.register(controllerName, thirdPartyControllers[controllerName]);
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
export {
  registerControllers,
  startStimulusApp
};
