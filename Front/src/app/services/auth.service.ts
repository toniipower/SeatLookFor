import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { BehaviorSubject, Observable, tap } from 'rxjs';
import { Router } from '@angular/router';
import { Usuario } from '../models/usuario.model';

interface AuthResponse {
  user: Usuario;
  token: string;
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'https://seatlookadmin.duckdns.org/api';

  private isAuthenticatedSubject = new BehaviorSubject<boolean>(false);
  isAuthenticated$ = this.isAuthenticatedSubject.asObservable();

  private currentUserSubject = new BehaviorSubject<Usuario | null>(null);
  currentUser$ = this.currentUserSubject.asObservable();

  constructor(
    private http: HttpClient,
    private router: Router
  ) {
    this.restoreSession();
  }

  private restoreSession() {
    const token = localStorage.getItem('token');
    if (token) {
      this.getCurrentUser().subscribe({
        next: user => {
          this.currentUserSubject.next(user);
          this.isAuthenticatedSubject.next(true);
        },
        error: () => {
          this.logout().subscribe(); // token inválido
        }
      });
    }
  }

  login(email: string, password: string): Observable<any> {
    return this.http.post<AuthResponse>(`${this.apiUrl}/login`, { email, password }).pipe(
      tap(response => {
        localStorage.setItem('token', response.token);
        this.currentUserSubject.next(response.user);
        this.isAuthenticatedSubject.next(true);

        if (response.user.admin) {
          window.location.href = 'https://seatlookadmin.duckdns.org/establecimientos';
        } else {
          this.router.navigate(['/']);
        }
      })
    );
  }
/*
  logout(): Observable<any> {
    return this.http.post(`${this.apiUrl}/logout`, {}, {
      headers: this.getAuthHeaders()
    }).pipe(
      tap(() => {
        localStorage.removeItem('token');
        this.currentUserSubject.next(null);
        this.isAuthenticatedSubject.next(false);
        this.router.navigate(['/']);
      })
    );
  }*/

logout(): void {
    this.clearSession();
    this.router.navigate(['/']);
  }

private clearSession() {
    this.currentUserSubject.next(null);
    this.isAuthenticatedSubject.next(false);
    sessionStorage.removeItem(this.USER_KEY);
  }

  getCurrentUser(): Observable<Usuario> {
    return this.http.get<Usuario>(`${this.apiUrl}/user`, {
      headers: this.getAuthHeaders()
    });
  }

  getAuthHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    });
  }

  isLoggedIn(): boolean {
    return this.isAuthenticatedSubject.value;
  }

  isAdmin(): boolean {
    const user = this.currentUserSubject.value;
    return user?.admin === true;
  }

  get currentUserValue(): Usuario | null {
    return this.currentUserSubject.value;
  }

  register(nombre: string, apellido: string, email: string, password: string): Observable<any> {
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
        localStorage.setItem('token', response.token);
        this.currentUserSubject.next(response.user);
        this.isAuthenticatedSubject.next(true);
        this.router.navigate(['/']);
      })
    );
  }
}
