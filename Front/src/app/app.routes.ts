import { Routes } from '@angular/router';
import { LandingPageComponent } from './components/landing-page/landing-page.component';
import { TodosEventosComponent } from './components/eventos/todos-eventos/todos-eventos.component';
import { AboutComponent } from './components/about/about.component';
import { UsuarioComponent } from './components/usuario/usuario.component';
import { EventosPersonalizadosComponent } from './components/eventos/eventos-personalizados/eventos-personalizados.component';

export const routes: Routes = [
  { path: '', component: LandingPageComponent },
  { path: 'eventos', component: TodosEventosComponent },
  { path: 'about', component: AboutComponent },
  { path: 'usuario', component: UsuarioComponent },
  { path: 'reserva', component: EventosPersonalizadosComponent },
  { path: 'evento/:id', component: EventosPersonalizadosComponent },
  // { path: 'asientos', component: AsientosComponent }

];
