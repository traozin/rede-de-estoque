import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import React from 'react';
import '../css/app.css';

createInertiaApp({
  resolve: name => {
    return import(`./pages/${name}.jsx`).then(module => module.default);
  },
  setup({ el, App, props }) {
    createRoot(el).render(React.createElement(App, props));
  },
});
