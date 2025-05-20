import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { catchError, map, Observable, throwError } from 'rxjs';
import { Evento } from '../models/evento.model';

@Injectable({
  providedIn: 'root'
})
export class EventoService {
  private apiUrl = 'http://localhost/api/eventos'; 

  constructor(private http: HttpClient) {}

  getEventos(): Observable<Evento[]> {
    const headers = new HttpHeaders({
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    });

    console.log('Realizando petición a:', this.apiUrl);

    return this.http.get<any>(this.apiUrl, { 
      headers
    }).pipe(
      map(response => {
        console.log('Respuesta del servidor:', response);
        if (response && response.data) {
          console.log("No es una array");
          
          return response.data;
        }
        if (Array.isArray(response)) {
          console.log("Es un array");

          return response;
        }
        
        return response;
      }),
      catchError((error: HttpErrorResponse) => {
        console.error('Error en la petición:', error);
        if (error.error instanceof ErrorEvent) {
          console.error('Error del cliente:', error.error.message);
        } else {
          console.error(`Código de error ${error.status}, ` + `mensaje: ${error.message}`);
        }
        return throwError(() => new Error('Error al obtener los eventos'));
      })
    );
  }
} 