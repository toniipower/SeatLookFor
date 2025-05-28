import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../../services/auth.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css'
})
export class LoginComponent {
  email = '';
  password = '';
  errorMessage = '';
  loading = false;

  constructor(
    private auth: AuthService,
    private router: Router
  ) {
    console.log('LoginComponent inicializado');
  }

  login() {
    console.log('Iniciando proceso de login');
    this.loading = true;
    this.errorMessage = '';

    console.log('Datos de login:', { email: this.email, passwordLength: this.password.length });

    this.auth.login(this.email, this.password).subscribe({
      next: (res) => {
        console.log('Login exitoso, respuesta:', res);
        this.loading = false;
        // La redirección se maneja en el servicio
      },
      error: (err) => {
        console.error('Error en login:', err);
        this.loading = false;
        this.errorMessage = err.error?.message || 'Error al iniciar sesión';
        if (err.status === 401) {
          this.errorMessage = 'Credenciales incorrectas';
        } else if (err.status === 0) {
          this.errorMessage = 'No se pudo conectar con el servidor';
        }
        console.log('Mensaje de error mostrado:', this.errorMessage);
      }
    });
  }
}
