import React from 'react';
import { InertiaApp } from '@inertiajs/inertia-react';
import ReactDOM from 'react-dom';
import '../css/app.css';

const app = document.getElementById('app');

const initialPage = JSON.parse(app.dataset.page);

ReactDOM.createRoot(app).render(
  <InertiaApp initialPage={initialPage} />
);
