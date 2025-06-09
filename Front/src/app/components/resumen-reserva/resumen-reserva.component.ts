import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { NavbarComponent } from '../navbar/navbar.component';
import { FooterComponent } from '../footer/footer.component';

interface Asiento {
  zona: string;
  ejeX: number;
  ejeY: number;
  precio: number;
}

interface Evento {
  titulo: string;
  fecha: string;
  hora: string;
}

interface Establecimiento {
  nombre: string;
  imagen: string;
  direccion: string;
}

interface DatosEvento {
  evento: Evento;
  establecimiento: Establecimiento;
}

@Component({
  selector: 'app-resumen-reserva',
  imports: [NavbarComponent, FooterComponent],
  templateUrl: './resumen-reserva.component.html',
  styleUrl: './resumen-reserva.component.css'
})
export class ResumenReservaComponent implements OnInit {
  datosEvento: DatosEvento | null = null;
  asientosSeleccionados: Asiento[] = [];

  constructor(private router: Router) {}

  ngOnInit(): void {
    // Aquí se cargarían los datos del evento y los asientos seleccionados
    // Por ejemplo, desde un servicio o desde el estado de la aplicación
    this.cargarDatosReserva();
  }

  cargarDatosReserva(): void {
    // TODO: Implementar la carga de datos desde el servicio correspondiente
    // Por ahora, datos de ejemplo
    this.datosEvento = {
      evento: {
        titulo: 'Concierto de Ejemplo',
        fecha: '2024-04-01',
        hora: '20:00'
      },
      establecimiento: {
        nombre: 'Sala de Conciertos',
        imagen: 'assets/images/establecimiento.jpg',
        direccion: 'Calle Ejemplo, 123'
      }
    };
  }

  calcularTotal(): number {
    return this.asientosSeleccionados.reduce((total, asiento) => total + asiento.precio, 0);
  }

  volverAEvento(): void {
    this.router.navigate(['/eventos-personalizados']);
  }

  confirmarReserva(): void {
    // TODO: Implementar la lógica de confirmación de reserva
    console.log('Confirmando reserva...');
  }
}
