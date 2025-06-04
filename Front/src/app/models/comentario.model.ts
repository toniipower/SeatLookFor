export interface Comentario {
    idCom: number;
    opinion: string;
    valoracion: number;
    foto?: string;
    idUsu: number;
    idAsi: number;
    usuario?: {
      nombre: string;
      apellido: string;
    };
  }