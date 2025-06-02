import { Routes } from '@angular/router';
import { LandingPageComponent } from './components/landing-page/landing-page.component';
import { TodosEventosComponent } from './components/eventos/todos-eventos/todos-eventos.component';
import { AboutComponent } from './components/about/about.component';
import { UsuarioComponent } from './components/usuario/usuario.component';
import { EventosPersonalizadosComponent } from './components/eventos/eventos-personalizados/eventos-personalizados.component';
import { LoginComponent } from './components/auth/login/login.component';
import { RegistroComponent } from './components/auth/registro/registro.component';
import { authGuard } from './guards/auth.guard';

export const routes: Routes = [
  { path: '', component: LandingPageComponent },
  { path: 'eventos', component: TodosEventosComponent },
  { path: 'evento/:id', component: EventosPersonalizadosComponent },
  { path: 'about', component: AboutComponent },
  { 
    path: 'usuario', 
    component: UsuarioComponent,
    canActivate: [authGuard]
  },
  { 
    path: 'reserva', 
    component: EventosPersonalizadosComponent,
    canActivate: [authGuard]
  },
  { path: 'login', component: LoginComponent },
  { path: 'registro', component: RegistroComponent },
  // { path: 'asientos', component: AsientosComponent }
  

];
