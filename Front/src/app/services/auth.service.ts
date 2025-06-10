import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, tap, catchError, throwError } from 'rxjs';
import { Router } from '@angular/router';
import { Usuario } from '../models/usuario.model';
import { environment } from '../../environments/environment';

interface AuthResponse {
  user: Usuario;
  token: string;
  message: string;
}

/* headers: {
  Authorization: `Bearer ${token}`
} */

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = environment.apiUrl;
  private isAuthenticatedSubject = new BehaviorSubject<boolean>(false);
  isAuthenticated$ = this.isAuthenticatedSubject.asObservable();
  private currentUserSubject = new BehaviorSubject<Usuario | null>(null);
  currentUser$ = this.currentUserSubject.asObservable();

  constructor(
    private http: HttpClient,
    private router: Router
  ) {
    // Verificar si hay un token guardado
    const token = localStorage.getItem('token');
    if (token) {
      this.isAuthenticatedSubject.next(true);
      // Cargar el usuario actual
      this.getCurrentUser().subscribe();
    }
  }

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': token ? `Bearer ${token}` : ''
    });
  }

  login(email: string, password: string): Observable<AuthResponse> {
    return this.http.post<AuthResponse>(
      `${this.apiUrl}/login`,
      { email, password }
    ).pipe(
      tap(response => {
        if (response.token) {
          localStorage.setItem('token', response.token);
          this.currentUserSubject.next(response.user);
          this.isAuthenticatedSubject.next(true);

          if (response.user.admin) {
            window.location.href = `${environment.apiUrl.replace('/api', '')}/establecimientos`;
          } else {
            this.router.navigate(['/']);
          }
        }
      }),
      catchError(error => {
        console.error('Error en login:', error);
        this.currentUserSubject.next(null);
        this.isAuthenticatedSubject.next(false);
        return throwError(() => error);
      })
    );
  }

  logout(): Observable<any> {
    return this.http.post(
      `${this.apiUrl}/logout`,
      {},
      { headers: this.getHeaders() }
    ).pipe(
      tap(() => {
        localStorage.removeItem('token');
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

  isLoggedIn(): boolean {
    return !!localStorage.getItem('token');
  }

  isAdmin(): boolean {
    const user = this.currentUserSubject.value;
    return user?.admin === true;
  }

  getCurrentUser(): Observable<Usuario> {
    return this.http.get<Usuario>(`${this.apiUrl}/user`, {
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

  register(nombre: string, apellido: string, email: string, password: string): Observable<AuthResponse> {
    return this.http.post<AuthResponse>(
      `${this.apiUrl}/register`,
      { 
        nombre, 
        apellido, 
        email, 
        password,
        password_confirmation: password 
      }
    ).pipe(
      tap(response => {
        if (response.token) {
          localStorage.setItem('token', response.token);
          this.currentUserSubject.next(response.user);
          this.isAuthenticatedSubject.next(true);
          this.router.navigate(['/']);
        }
      }),
      catchError(error => {
        console.error('Error en registro:', error);
        return throwError(() => error);
      })
    );
  }

  get currentUserValue(): Usuario | null {
    return this.currentUserSubject.value;
  }
}
