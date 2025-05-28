import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, tap } from 'rxjs';
import { Router } from '@angular/router';
import { Usuario } from '../models/usuario.model';

interface AuthResponse {
  token: string;
  user: Usuario;
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'http://localhost/api'; // tu backend
  private isAuthenticatedSubject = new BehaviorSubject<boolean>(false);
  isAuthenticated$ = this.isAuthenticatedSubject.asObservable();
  private currentUserSubject = new BehaviorSubject<Usuario | null>(null);
  currentUser$ = this.currentUserSubject.asObservable();

  constructor(
    private http: HttpClient,
    private router: Router
  ) {
    console.log('AuthService inicializado');
    this.checkAuthStatus();
  }

  login(email: string, password: string): Observable<AuthResponse> {
    console.log('Intentando login con:', { email });
    return this.http.post<AuthResponse>(`${this.apiUrl}/login`, { email, password }).pipe(
      tap({
        next: (response: AuthResponse) => {
          console.log('Respuesta del servidor:', response);
          if (response.token) {
            console.log('Token recibido:', response.token);
            this.saveToken(response.token);
            this.currentUserSubject.next(response.user);
            this.isAuthenticatedSubject.next(true);
            console.log('Token guardado en localStorage:', localStorage.getItem('auth_token'));
            
            // Redirigir basado en el rol de administrador
            if (response.user.admin) {
              console.log('Usuario es administrador, redirigiendo al dashboard');
              window.location.href = 'http://localhost'; // Redirección al dashboard de Laravel
            } else {
              console.log('Usuario normal, redirigiendo a la página de usuario');
              this.router.navigate(['/usuario']);
            }
          } else {
            console.error('No se recibió token en la respuesta');
          }
        },
        error: (error) => {
          console.error('Error en login:', error);
        }
      })
    );
  }

  logout() {
    console.log('Ejecutando logout');
    localStorage.removeItem('auth_token');
    this.currentUserSubject.next(null);
    this.isAuthenticatedSubject.next(false);
    this.router.navigate(['/login']);
  }

  saveToken(token: string) {
    console.log('Guardando token:', token);
    try {
      localStorage.setItem('auth_token', token);
      console.log('Token guardado exitosamente');
    } catch (error) {
      console.error('Error al guardar token:', error);
    }
  }

  getToken(): string | null {
    const token = localStorage.getItem('auth_token');
    console.log('Token actual:', token);
    return token;
  }

  isLoggedIn(): boolean {
    const isLogged = !!this.getToken();
    console.log('¿Está logueado?:', isLogged);
    return isLogged;
  }

  isAdmin(): boolean {
    const user = this.currentUserSubject.value;
    return user?.admin === true;
  }

  private checkAuthStatus() {
    console.log('Verificando estado de autenticación');
    const token = this.getToken();
    if (token) {
      console.log('Token encontrado, verificando usuario');
      this.getCurrentUser().subscribe({
        next: (user) => {
          console.log('Usuario verificado:', user);
          this.currentUserSubject.next(user);
          this.isAuthenticatedSubject.next(true);
        },
        error: (error) => {
          console.error('Error al verificar usuario:', error);
          this.logout();
        }
      });
    } else {
      console.log('No hay token, usuario no autenticado');
      this.isAuthenticatedSubject.next(false);
    }
  }

  // Método para obtener el usuario actual
  getCurrentUser(): Observable<Usuario> {
    console.log('Obteniendo usuario actual');
    return this.http.get<Usuario>(`${this.apiUrl}/user`);
  }
}
