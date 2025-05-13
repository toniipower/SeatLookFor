import { Routes } from '@angular/router';
import { TodosEventosComponent } from './seatlookfor-app/components/eventos/todos-eventos/todos-eventos.component';
import { LandingPageComponent } from './seatlookfor-app/components/landing-page/landing-page.component';

export const routes: Routes = [
  { path: '', component: LandingPageComponent },
  { path: 'eventos', component: TodosEventosComponent }
];
