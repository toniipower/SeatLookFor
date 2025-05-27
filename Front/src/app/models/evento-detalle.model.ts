import { Establecimiento } from "./establecimiento.model";
import { Evento } from "./evento.model";


export interface EventoDetalle {
  evento: Evento
  establecimiento : Establecimiento
  // idEve: number;
  // titulo: string;
  // fecha: string; // Usa `Date` si conviertes la fecha en el front
  // valoracion: number;
  // descripcion: string;
}
