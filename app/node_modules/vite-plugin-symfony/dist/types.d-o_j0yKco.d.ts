import { ControllerConstructor } from '@hotwired/stimulus';
import { FunctionComponent, ComponentClass } from 'react';
import { Component } from 'vue';
import { SvelteComponent } from 'svelte';

type LazyModule<M> = () => Promise<M>;
type ImportedModule<M> = M | LazyModule<M>;
type ImportedModules<M> = Record<string, ImportedModule<M>>;

type ControllerModule = {
  default: ControllerConstructor;
};

type ReactComponent = string | FunctionComponent<object> | ComponentClass<object, any>;
type ReactModule = {
  default: ReactComponent;
};

type VueModule = {
  default: Component;
};

type SvelteModule = {
  default: SvelteComponent;
};

declare global {
  function resolveReactComponent(name: string): ImportedModule<ReactModule>;
  function resolveVueComponent(name: string): Component;
  function resolveSvelteComponent(name: string): ImportedModule<SvelteModule>;

  interface Window {
    resolveReactComponent(name: string): ImportedModule<ReactModule>;
    resolveVueComponent(name: string): Component;
    resolveSvelteComponent(name: string): ImportedModule<SvelteModule>;
  }
}

export type { ControllerModule as C, ImportedModules as I, ReactModule as R, SvelteModule as S, VueModule as V };
