import { Routes } from '@angular/router';
import { LandingPageComponent } from './components/landing-page/landing-page.component';
import { TodosEventosComponent } from './components/eventos/todos-eventos/todos-eventos.component';

export const routes: Routes = [
  { path: '', component: LandingPageComponent },
  { path: 'eventos', component: TodosEventosComponent },
];
