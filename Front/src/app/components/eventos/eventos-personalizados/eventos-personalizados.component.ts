import { Component } from '@angular/core';
import { NavbarComponent } from '../../navbar/navbar.component';
import { FooterComponent } from '../../footer/footer.component';
import { EventoDetalle } from '../../../models/evento-detalle.model';
import { ActivatedRoute } from '@angular/router';
import { EventoService } from '../../../services/evento.service';
import { AsientosComponent } from '../../asientos/asientos.component';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'eventos-personalizados',
  imports: [NavbarComponent, FooterComponent, AsientosComponent, CommonModule],
  templateUrl: './eventos-personalizados.component.html',
  styleUrl: './eventos-personalizados.component.css'
})
export class EventosPersonalizadosComponent {

  titulo: String = "Matilda";
  datosEvento?: EventoDetalle;

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
}
