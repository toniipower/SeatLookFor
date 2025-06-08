import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';
import { Comentario } from '../models/comentario.model';

@Injectable({
  providedIn: 'root'
})
export class ComentarioService {
  // private apiUrl = `${environment.apiUrl}/comentarios`;
  private apiUrl = 'http://localhost';

  constructor(private http: HttpClient) { }

  crearComentario(comentario: FormData): Observable<Comentario> {
    return this.http.post<Comentario>(this.apiUrl, comentario, {
      withCredentials: true
    });
  }

  getComentariosAsiento(idAsiento: number): Observable<Comentario[]> {
    return this.http.get<Comentario[]>(`${this.apiUrl}/asiento/${idAsiento}`, {
      withCredentials: true
    });
  }

  eliminarComentario(idComentario: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${idComentario}`, {
      withCredentials: true
    });
  }

  getImagenAsiento(): Observable<string> {
    return this.http.get<string>(`${this.apiUrl}/imagenes/asiento1`, {
      withCredentials: true
    });
  }
} 

