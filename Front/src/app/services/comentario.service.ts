import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';
import { Comentario } from '../models/comentario.model';

@Injectable({
  providedIn: 'root'
})
export class ComentarioService {
  private apiUrl = 'http://localhost/api';

  constructor(private http: HttpClient) { }

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    });
  }

  crearComentario(idAsi: number, comentario: { opinion: string, valoracion: number, foto?: string }): Observable<Comentario> {
    return this.http.post<Comentario>(
      `${this.apiUrl}/asientos/${idAsi}/comentar`, 
      comentario,
      { headers: this.getHeaders() }
    );
  }

  getComentariosAsiento(idAsiento: number): Observable<Comentario[]> {
    return this.http.get<Comentario[]>(
      `${this.apiUrl}/asientos/${idAsiento}/comentarios`,
      { headers: this.getHeaders() }
    );
  }

  eliminarComentario(idComentario: number): Observable<void> {
    return this.http.delete<void>(
      `${this.apiUrl}/comentarios/${idComentario}`,
      { headers: this.getHeaders() }
    );
  }

  getImagenAsiento(): Observable<string> {
    return this.http.get<string>(`${this.apiUrl}/imagenes/asiento1`, {
      withCredentials: true
    });
  }
} 

