import { Component, OnInit } from '@angular/core';
import { NavbarComponent } from '../../navbar/navbar.component';
import { FooterComponent } from '../../footer/footer.component';
import { EventoDetalle } from '../../../models/evento-detalle.model';
import { ActivatedRoute } from '@angular/router';
import { EventoService } from '../../../services/evento.service';
import { AsientosComponent } from '../../asientos/asientos.component';
import { CommonModule } from '@angular/common';
import { Asiento } from '../../../models/asiento.model';

@Component({
  selector: 'eventos-personalizados',
  imports: [NavbarComponent, FooterComponent, AsientosComponent, CommonModule],
  templateUrl: './eventos-personalizados.component.html',
  styleUrl: './eventos-personalizados.component.css'
})
export class EventosPersonalizadosComponent implements OnInit {

  titulo: String = "Matilda";
  datosEvento?: EventoDetalle;
  asientosSeleccionados: Asiento[] = [];

  constructor(
    private route: ActivatedRoute,
    private eventoService: EventoService
  ) { }

  ngOnInit(): void {
    const id = Number(this.route.snapshot.paramMap.get('id'));
    if (id) {
      this.eventoService.getEventoDetalle(id).subscribe({
        next: (data) => {
          this.datosEvento = data;
          console.log(this.datosEvento);
          
        },
        error: (err) => {
          console.error(err);
        }
      });
    }
  }

  onAsientosSeleccionados(asientos: Asiento[]) {
    this.asientosSeleccionados = asientos;
  }

  eliminarAsiento(asiento: Asiento) {
    const index = this.asientosSeleccionados.findIndex(a => 
      a.ejeX === asiento.ejeX && a.ejeY === asiento.ejeY
    );
    if (index !== -1) {
      this.asientosSeleccionados.splice(index, 1);
    }
  }

  calcularTotal(): number {
    return this.asientosSeleccionados.reduce((total, asiento) => total + asiento.precio, 0);
  }
}
