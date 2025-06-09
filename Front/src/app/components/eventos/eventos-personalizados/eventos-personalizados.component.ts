import { Component, OnInit } from '@angular/core';
import { NavbarComponent } from '../../navbar/navbar.component';
import { FooterComponent } from '../../footer/footer.component';
import { EventoDetalle } from '../../../models/evento-detalle.model';
import { ActivatedRoute } from '@angular/router';
import { EventoService } from '../../../services/evento.service';
import { AsientosComponent } from '../../asientos/asientos.component';
import { CommonModule } from '@angular/common';
import { Asiento } from '../../../models/asiento.model';
// import { ComentarioFormComponent } from '../../comentario-form/comentario-form.component';

@Component({
  selector: 'eventos-personalizados',
  standalone: true,
  imports: [NavbarComponent, FooterComponent, AsientosComponent, CommonModule],
  templateUrl: './eventos-personalizados.component.html',
  styleUrl: './eventos-personalizados.component.css'
})
export class EventosPersonalizadosComponent implements OnInit {

  titulo: String = "Matilda";
  datosEvento?: EventoDetalle;
  asientosSeleccionados: Asiento[] = [];
  asientoSeleccionado: Asiento | null = null;

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
    if (asientos.length > 0) {
      this.asientoSeleccionado = asientos[asientos.length - 1];
    } else {
      this.asientoSeleccionado = null;
    }
  }

  eliminarAsiento(asiento: Asiento) {
    const index = this.asientosSeleccionados.findIndex(a => 
      a.ejeX === asiento.ejeX && a.ejeY === asiento.ejeY
    );
    if (index !== -1) {
      this.asientosSeleccionados.splice(index, 1);
      if (this.asientoSeleccionado?.idAsi === asiento.idAsi) {
        this.asientoSeleccionado = this.asientosSeleccionados.length > 0 ? 
          this.asientosSeleccionados[this.asientosSeleccionados.length - 1] : null;
      }
    }
  }

  calcularTotal(): number {
    return this.asientosSeleccionados.reduce((total, asiento) => {
      const precio = typeof asiento.precio === 'string' ? parseFloat(asiento.precio) : asiento.precio;
      return total + precio;
    }, 0);
  }

  onComentarioCreado() {
    // Recargar los comentarios del asiento
    if (this.asientoSeleccionado) {
      this.eventoService.getEventoDetalle(Number(this.route.snapshot.paramMap.get('id'))).subscribe({
        next: (data) => {
          this.datosEvento = data;
          const asientoActualizado = this.datosEvento?.establecimiento?.asientos?.find(
            a => a.idAsi === this.asientoSeleccionado?.idAsi
          );
          if (asientoActualizado) {
            this.asientoSeleccionado = asientoActualizado;
          }
        },
        error: (err) => {
          console.error('Error al actualizar comentarios:', err);
        }
      });
    }
  }
}
