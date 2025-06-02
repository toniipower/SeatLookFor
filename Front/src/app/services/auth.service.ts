import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, tap, switchMap, catchError, throwError, of } from 'rxjs';
import { Router } from '@angular/router';
import { Usuario } from '../models/usuario.model';

interface AuthResponse {
  user: Usuario;
  message: string;
}

/* headers: {
  Authorization: `Bearer ${token}`
} */

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'http://localhost/api'; 
  private isAuthenticatedSubject = new BehaviorSubject<boolean>(false);
  isAuthenticated$ = this.isAuthenticatedSubject.asObservable();
  private currentUserSubject = new BehaviorSubject<Usuario | null>(null);
  currentUser$ = this.currentUserSubject.asObservable();

  constructor(
    private http: HttpClient,
    private router: Router
  ) {
    // No verificamos el estado de autenticación al inicio
    this.isAuthenticatedSubject.next(false);
    this.currentUserSubject.next(null);
  }

  private getCsrfToken(): Observable<any> {
    const headers = new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    });

    return this.http.get('http://localhost/sanctum/csrf-cookie', {
      withCredentials: true,
      headers
    }).pipe(
      tap(() => {
        console.log('CSRF token obtenido');
        // Verificar que la cookie XSRF-TOKEN se haya establecido
        const cookies = document.cookie.split(';');
        const xsrfCookie = cookies.find(cookie => cookie.trim().startsWith('XSRF-TOKEN='));
        console.log('Cookie XSRF-TOKEN:', xsrfCookie);
        
        if (!xsrfCookie) {
          console.error('No se encontró la cookie XSRF-TOKEN');
          throw new Error('No se pudo obtener el token CSRF');
        }
      }),
      catchError(error => {
        console.error('Error obteniendo CSRF token:', error);
        return throwError(() => error);
      })
    );
  }

  private getHeaders(): HttpHeaders {
    const cookies = document.cookie.split(';');
    const xsrfCookie = cookies.find(cookie => cookie.trim().startsWith('XSRF-TOKEN='));
    const token = xsrfCookie ? decodeURIComponent(xsrfCookie.split('=')[1]) : '';

    return new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-XSRF-TOKEN': token
    });
  }

  login(email: string, password: string): Observable<AuthResponse> {
    return this.getCsrfToken().pipe(
      switchMap(() => {
        console.log('Intentando login con headers:', this.getHeaders().keys());
        
        return this.http.post<AuthResponse>(
          `${this.apiUrl}/login`,
          { email, password },
          {
            withCredentials: true,
            headers: this.getHeaders()
          }
        ).pipe(
          tap({
            next: (response: AuthResponse) => {
              if (response.user) {
                this.currentUserSubject.next(response.user);
                this.isAuthenticatedSubject.next(true);

                if (response.user.admin) {
                  console.log('Usuario es administrador, redirigiendo al backend');
                  window.location.href = 'http://localhost/establecimientos';
                } else {
                  console.log('Usuario normal, redirigiendo a landing-page');
                  this.router.navigate(['/']);
                }
              } else {
                console.error('No se recibió usuario en la respuesta');
              }
            },
            error: (error) => {
              console.error('Error en login:', error);
              this.currentUserSubject.next(null);
              this.isAuthenticatedSubject.next(false);
            }
          }),
          catchError(error => {
            console.error('Error en login:', error);
            this.currentUserSubject.next(null);
            this.isAuthenticatedSubject.next(false);
            return throwError(() => error);
          })
        );
      })
    );
  }

  logout(): Observable<any> {
    return this.http.post(
      `${this.apiUrl}/logout`,
      {},
      {
        withCredentials: true,
        headers: this.getHeaders()
      }
    ).pipe(
      tap(() => {
        this.currentUserSubject.next(null);
        this.isAuthenticatedSubject.next(false);
        this.router.navigate(['/']);
      }),
      catchError(error => {
        console.error('Error en logout:', error);
        return throwError(() => error);
      })
    );
  }

  saveToken(token: string) {
    console.log('Guardando token:', token);
    try {
      document.cookie = `auth_token=${token}; path=/; Secure; SameSite=Strict`;
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
    return this.isAuthenticatedSubject.value;
  }

  isAdmin(): boolean {
    const user = this.currentUserSubject.value;
    return user?.admin === true;
  }

  getCurrentUser(): Observable<Usuario> {
    return this.http.get<Usuario>(`${this.apiUrl}/user`, {
      withCredentials: true,
      headers: this.getHeaders()
    }).pipe(
      tap(user => {
        this.currentUserSubject.next(user);
        this.isAuthenticatedSubject.next(true);
      }),
      catchError(error => {
        this.currentUserSubject.next(null);
        this.isAuthenticatedSubject.next(false);
        return throwError(() => error);
      })
    );
  }
}
