import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, tap, catchError, throwError } from 'rxjs';
import { Router } from '@angular/router';
import { Usuario } from '../models/usuario.model';

interface AuthResponse {
  user: Usuario;
  message: string;
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  // private apiUrl = 'https://seatlookadmin.duckdns.org/api';
  private apiUrl = 'http://localhost/api';
  private url = 'http://localhost:4200';
  private isAuthenticatedSubject = new BehaviorSubject<boolean>(false);
  isAuthenticated$ = this.isAuthenticatedSubject.asObservable();
  private currentUserSubject = new BehaviorSubject<Usuario | null>(null);
  currentUser$ = this.currentUserSubject.asObservable();
  private readonly USER_KEY = 'currentUser';

  constructor(
    private http: HttpClient,
    private router: Router
  ) {
    this.initializeAuthState();
  }

  private initializeAuthState() {
    const storedUser = sessionStorage.getItem(this.USER_KEY);
    if (storedUser) {
      const user = JSON.parse(storedUser);
      this.currentUserSubject.next(user);
      this.isAuthenticatedSubject.next(true);
      // Solo intentamos obtener el usuario actual si hay uno en session storage
      this.getCurrentUser().subscribe();
    }
  }

/*   getCSRFToken(): Observable<any> {
    return this.http.get('https://seatlookadmin.duckdns.org/sanctum/csrf-cookie', {
      withCredentials: true
    });
  } */
  getCSRFToken(): Observable<any> {
    return this.http.get('http://localhost/sanctum/csrf-cookie', {
      withCredentials: true
    });
  }

  login(email: string, password: string): Observable<any> {
    return this.getCSRFToken().pipe(
      tap(() => {
        this.http.post<AuthResponse>(
          `${this.apiUrl}/login`,
          { email, password },
          { withCredentials: true }
        ).subscribe({
          next: response => {
            this.currentUserSubject.next(response.user);
            this.isAuthenticatedSubject.next(true);

            if (response.user.admin) {
              // window.location.href = 'https://seatlookadmin.duckdns.org/establecimientos';
              window.location.href = 'http://localhost/establecimientos';
            } else {
              // Guardar en session storage solo para usuarios no admin
              sessionStorage.setItem(this.USER_KEY, JSON.stringify(response.user));
              this.router.navigate(['/']);
            }
          },
          error: error => {
            console.error('Error en login:', error);
            this.currentUserSubject.next(null);
            this.isAuthenticatedSubject.next(false);
            sessionStorage.removeItem(this.USER_KEY);
          }
        });
      })
    );
  }

  logout(): void {
    this.currentUserSubject.next(null);
    this.isAuthenticatedSubject.next(false);
    sessionStorage.removeItem(this.USER_KEY);
    this.router.navigate(['/']);
  }

  getCurrentUser(): Observable<Usuario> {
    return this.http.get<Usuario>(`${this.apiUrl}/user`, {
      withCredentials: true
    }).pipe(
      tap(user => {
        this.currentUserSubject.next(user);
        this.isAuthenticatedSubject.next(true);
        if (!user.admin) {
          sessionStorage.setItem(this.USER_KEY, JSON.stringify(user));
        }
      }),
      catchError(error => {
        this.currentUserSubject.next(null);
        this.isAuthenticatedSubject.next(false);
        sessionStorage.removeItem(this.USER_KEY);
        return throwError(() => error);
      })
    );
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
    return this.getCSRFToken().pipe(
      tap(() => {
        this.http.post<AuthResponse>(
          `${this.apiUrl}/register`,
          { nombre, apellido, email, password, password_confirmation: password },
          { withCredentials: true }
        ).subscribe({
          next: (response: AuthResponse) => {
            this.currentUserSubject.next(response.user);
            this.isAuthenticatedSubject.next(true);
            this.router.navigate(['/']);
          },
          error: (error) => {
            console.error('Error en registro:', error);
          }
        });
      })
    );
  }
  
}
