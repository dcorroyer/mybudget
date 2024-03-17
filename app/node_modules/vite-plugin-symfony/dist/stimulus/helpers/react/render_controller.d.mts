import { ReactElement } from 'react';
import { Controller } from '@hotwired/stimulus';

declare class export_default extends Controller {
    readonly componentValue?: string;
    readonly propsValue?: object;
    static values: {
        component: StringConstructor;
        props: ObjectConstructor;
    };
    connect(): void;
    disconnect(): void;
    _renderReactElement(reactElement: ReactElement): void;
    private dispatchEvent;
}

export { export_default as default };
